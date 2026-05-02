#!/usr/bin/env python3
"""Run lightweight code-quality checks tailored to a Laravel fullstack repository."""

from __future__ import annotations

import argparse
import json
import re
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Dict, Iterable, List, Sequence, Tuple


IGNORE_DIRS = {
    ".git",
    "node_modules",
    "vendor",
    "storage",
    "bootstrap/cache",
    "public/build",
    "dist",
    "build",
    "coverage",
}

CODE_EXTENSIONS = {".php", ".blade.php", ".js", ".ts", ".css", ".md"}
DEBUG_PATTERNS: Sequence[Tuple[str, str]] = (
    (r"\bdd\s*\(", "Laravel dump-and-die call"),
    (r"\bdump\s*\(", "Dump call left in code"),
    (r"console\.log\s*\(", "Console log statement"),
    (r"var_dump\s*\(", "PHP var_dump call"),
)


@dataclass
class Finding:
    severity: str
    file: str
    line: int
    rule: str
    message: str


class CodeQualityAnalyzer:
    def __init__(self, target_path: str) -> None:
        self.root = Path(target_path).resolve()

    def validate_target(self) -> None:
        if not self.root.exists():
            raise FileNotFoundError(f"Target path does not exist: {self.root}")
        if not self.root.is_dir():
            raise NotADirectoryError(f"Target path is not a directory: {self.root}")

    def analyze(self) -> Dict[str, object]:
        self.validate_target()
        findings: List[Finding] = []

        files = self._code_files()
        for path in files:
            findings.extend(self._scan_file(path))

        findings.extend(self._repository_level_findings())

        counts = {"high": 0, "medium": 0, "low": 0}
        for finding in findings:
            counts[finding.severity] += 1

        return {
            "status": "success",
            "target": str(self.root),
            "summary": {
                "files_scanned": len(files),
                "high": counts["high"],
                "medium": counts["medium"],
                "low": counts["low"],
                "total": len(findings),
            },
            "findings": [asdict(item) for item in findings],
        }

    def generate_report(self, results: Dict[str, object]) -> str:
        summary = results["summary"]
        lines = [
            f"Target: {results['target']}",
            f"Files scanned: {summary['files_scanned']}",
            f"Findings: {summary['total']} total ({summary['high']} high, {summary['medium']} medium, {summary['low']} low)",
            "",
            "Findings",
        ]

        findings = results["findings"]
        if not findings:
            lines.append("- No findings detected")
            return "\n".join(lines)

        for item in findings:
            location = f"{item['file']}:{item['line']}" if item["line"] else item["file"]
            lines.append(
                f"- [{item['severity'].upper()}] {location} {item['rule']}: {item['message']}"
            )

        return "\n".join(lines)

    def _code_files(self) -> List[Path]:
        items: List[Path] = []
        for path in self.root.rglob("*"):
            if not path.is_file() or self._is_ignored(path):
                continue
            suffix = self._compound_suffix(path)
            if suffix in CODE_EXTENSIONS:
                items.append(path)
        return items

    def _compound_suffix(self, path: Path) -> str:
        name = path.name
        if name.endswith(".blade.php"):
            return ".blade.php"
        return path.suffix

    def _is_ignored(self, path: Path) -> bool:
        relative = path.relative_to(self.root).as_posix()
        parts = relative.split("/")
        for ignored in IGNORE_DIRS:
            if relative == ignored or relative.startswith(ignored + "/"):
                return True
            if ignored in parts:
                return True
        return False

    def _scan_file(self, path: Path) -> List[Finding]:
        findings: List[Finding] = []
        relative = path.relative_to(self.root).as_posix()

        try:
            content = path.read_text(encoding="utf-8", errors="ignore")
        except OSError:
            return findings

        lines = content.splitlines()
        for index, line in enumerate(lines, start=1):
            findings.extend(self._scan_debug_patterns(relative, index, line))
            findings.extend(self._scan_todo(relative, index, line))
            findings.extend(self._scan_closure_routes(relative, index, line))

        findings.extend(self._scan_large_files(relative, lines))
        findings.extend(self._scan_placeholder_docs(relative, content))
        return findings

    def _scan_debug_patterns(self, relative: str, line_no: int, line: str) -> List[Finding]:
        findings: List[Finding] = []
        for pattern, message in DEBUG_PATTERNS:
            if re.search(pattern, line):
                findings.append(
                    Finding(
                        severity="medium",
                        file=relative,
                        line=line_no,
                        rule="debug-statement",
                        message=message,
                    )
                )
        return findings

    def _scan_todo(self, relative: str, line_no: int, line: str) -> List[Finding]:
        if "TODO" not in line and "FIXME" not in line:
            return []
        return [
            Finding(
                severity="low",
                file=relative,
                line=line_no,
                rule="todo-marker",
                message="Unresolved TODO/FIXME marker",
            )
        ]

    def _scan_closure_routes(self, relative: str, line_no: int, line: str) -> List[Finding]:
        if relative != "routes/web.php":
            return []
        if "Route::" in line and "function" in line:
            return [
                Finding(
                    severity="medium",
                    file=relative,
                    line=line_no,
                    rule="closure-route",
                    message="Route closure found. Prefer controller actions once the flow becomes non-trivial.",
                )
            ]
        return []

    def _scan_large_files(self, relative: str, lines: List[str]) -> List[Finding]:
        if len(lines) <= 350:
            return []
        return [
            Finding(
                severity="low",
                file=relative,
                line=0,
                rule="large-file",
                message=f"File is large at {len(lines)} lines. Consider splitting when responsibilities diverge.",
            )
        ]

    def _scan_placeholder_docs(self, relative: str, content: str) -> List[Finding]:
        if relative != "README.md":
            return []
        if "Laravel is a web application framework" in content:
            return [
                Finding(
                    severity="medium",
                    file=relative,
                    line=1,
                    rule="placeholder-readme",
                    message="README still contains default Laravel boilerplate instead of project-specific guidance.",
                )
            ]
        return []

    def _repository_level_findings(self) -> List[Finding]:
        findings: List[Finding] = []

        workflow_dir = self.root / ".github" / "workflows"
        if not workflow_dir.exists() or not any(workflow_dir.glob("*.yml")):
            findings.append(
                Finding(
                    severity="high",
                    file=".github/workflows",
                    line=0,
                    rule="missing-ci",
                    message="No CI workflow detected for Composer install, frontend build, or tests.",
                )
            )

        tests_dir = self.root / "tests"
        test_files = list(tests_dir.rglob("*Test.php")) if tests_dir.exists() else []
        if len(test_files) <= 2:
            findings.append(
                Finding(
                    severity="high",
                    file="tests",
                    line=0,
                    rule="thin-test-suite",
                    message="Test coverage appears minimal. Add feature and domain tests before major product work.",
                )
            )

        if not (self.root / "references").exists():
            findings.append(
                Finding(
                    severity="low",
                    file="references",
                    line=0,
                    rule="missing-internal-docs",
                    message="Internal architecture and workflow references are missing.",
                )
            )

        return findings


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Run repository-focused code-quality checks.")
    parser.add_argument("target_path", nargs="?", default=".", help="Project directory to scan")
    parser.add_argument("--json", action="store_true", help="Print machine-readable JSON")
    parser.add_argument("--output", help="Write the report to a file")
    return parser


def main() -> int:
    parser = build_parser()
    args = parser.parse_args()

    analyzer = CodeQualityAnalyzer(args.target_path)
    results = analyzer.analyze()

    if args.json:
        output = json.dumps(results, indent=2)
    else:
        output = analyzer.generate_report(results)

    if args.output:
        Path(args.output).write_text(output + "\n", encoding="utf-8")
    else:
        print(output)

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
