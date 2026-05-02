#!/usr/bin/env python3
"""Analyze a repository and recommend fullstack project structure improvements."""

from __future__ import annotations

import argparse
import json
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Dict, Iterable, List, Optional


DEFAULT_IGNORE = {
    ".git",
    "node_modules",
    "vendor",
    "storage",
    "bootstrap/cache",
    "public/build",
    "dist",
    "build",
    "coverage",
    ".idea",
    ".vscode",
}


@dataclass
class Recommendation:
    category: str
    title: str
    detail: str
    severity: str


class ProjectScaffolder:
    def __init__(self, target_path: str, verbose: bool = False) -> None:
        self.root = Path(target_path).resolve()
        self.verbose = verbose

    def validate_target(self) -> None:
        if not self.root.exists():
            raise FileNotFoundError(f"Target path does not exist: {self.root}")
        if not self.root.is_dir():
            raise NotADirectoryError(f"Target path is not a directory: {self.root}")

    def analyze(self) -> Dict[str, object]:
        self.validate_target()

        repo_files = self._list_files()
        stack = self._detect_stack(repo_files)
        domain = self._detect_domain(repo_files)
        health = self._summarize_health(repo_files)
        missing = self._missing_structure(repo_files, stack)
        recommendations = self._build_recommendations(repo_files, stack, missing)

        return {
            "status": "success",
            "target": str(self.root),
            "project": {
                "name": self.root.name,
                "stack": stack,
                "domain": domain,
            },
            "health": health,
            "missing_structure": missing,
            "recommendations": [asdict(item) for item in recommendations],
        }

    def generate_report(self, results: Dict[str, object]) -> str:
        project = results["project"]
        health = results["health"]
        lines = [
            f"Project: {project['name']}",
            f"Target: {results['target']}",
            f"Domain: {project['domain']}",
            f"Primary stack: {', '.join(project['stack']) if project['stack'] else 'Unknown'}",
            "",
            "Health Summary",
            f"- Total files scanned: {health['total_files']}",
            f"- Test files: {health['test_files']}",
            f"- CI workflows: {health['ci_workflows']}",
            f"- Docs detected: {health['docs_files']}",
            "",
            "Missing Structure",
        ]

        missing = results["missing_structure"]
        if missing:
            for item in missing:
                lines.append(f"- {item}")
        else:
            lines.append("- No major structure gaps detected")

        lines.extend(["", "Recommendations"])

        recommendations = results["recommendations"]
        if recommendations:
            for item in recommendations:
                lines.append(
                    f"- [{item['severity'].upper()}] {item['title']}: {item['detail']}"
                )
        else:
            lines.append("- No recommendations. Structure looks solid.")

        return "\n".join(lines)

    def _list_files(self) -> List[Path]:
        files: List[Path] = []
        for path in self.root.rglob("*"):
            if not path.is_file():
                continue
            if self._is_ignored(path):
                continue
            files.append(path)
        return files

    def _is_ignored(self, path: Path) -> bool:
        relative = path.relative_to(self.root).as_posix()
        parts = relative.split("/")
        for ignored in DEFAULT_IGNORE:
            if relative == ignored or relative.startswith(ignored + "/"):
                return True
            if ignored in parts:
                return True
        return False

    def _detect_stack(self, files: Iterable[Path]) -> List[str]:
        stack: List[str] = []
        file_set = {path.relative_to(self.root).as_posix() for path in files}
        composer_data = self._read_text(self.root / "composer.json").lower()
        package_data = self._read_text(self.root / "package.json").lower()

        if "composer.json" in file_set:
            stack.append("PHP")
        if "artisan" in file_set:
            stack.append("Laravel")
        if any(path.endswith(".blade.php") for path in file_set):
            stack.append("Blade")
        if "package.json" in file_set:
            stack.append("Node.js")
        if "vite.config.js" in file_set or "vite.config.ts" in file_set:
            stack.append("Vite")
        if "laravel-vite-plugin" in package_data and "Vite" not in stack:
            stack.append("Vite")
        if any(path.endswith(".ts") for path in file_set):
            stack.append("TypeScript")
        if "tailwindcss" in package_data or any("tailwind" in path.lower() for path in file_set):
            stack.append("Tailwind CSS")
        if "filament/filament" in composer_data or any("filament" in path.lower() for path in file_set):
            stack.append("Filament")
        if "spatie/laravel-permission" in composer_data:
            stack.append("Spatie Permission")
        if any("livewire" in path.lower() for path in file_set):
            stack.append("Livewire")

        return stack

    def _read_text(self, path: Path) -> str:
        if not path.exists():
            return ""
        try:
            return path.read_text(encoding="utf-8", errors="ignore")
        except OSError:
            return ""

    def _detect_domain(self, files: Iterable[Path]) -> str:
        candidates = [
            self.root / "Alliago.id_visa_platform_development_plan.md",
            self.root / "README.md",
        ]
        joined = ""
        for candidate in candidates:
            if candidate.exists():
                try:
                    joined += candidate.read_text(encoding="utf-8", errors="ignore").lower()
                except OSError:
                    continue
        if "visa" in joined:
            return "Visa assistance platform"
        if "ecommerce" in joined or "shop" in joined:
            return "Commerce application"
        return "General web application"

    def _summarize_health(self, files: Iterable[Path]) -> Dict[str, int]:
        relative = [path.relative_to(self.root).as_posix() for path in files]
        return {
            "total_files": len(relative),
            "test_files": len([item for item in relative if item.startswith("tests/")]),
            "ci_workflows": len([item for item in relative if item.startswith(".github/workflows/")]),
            "docs_files": len(
                [item for item in relative if item.endswith(".md") or item.startswith("docs/")]
            ),
        }

    def _missing_structure(self, files: Iterable[Path], stack: List[str]) -> List[str]:
        relative = {path.relative_to(self.root).as_posix() for path in files}
        missing: List[str] = []

        expected = []
        if "Laravel" in stack:
            expected.extend(
                [
                    "app/Http/Controllers",
                    "app/Models",
                    "resources/views",
                    "routes/web.php",
                    "tests/Feature",
                    "tests/Unit",
                ]
            )
        if "Filament" in stack:
            expected.append("app/Providers/Filament")
        if "TypeScript" in stack:
            expected.append("resources/js")
        expected.extend(["scripts", "references"])

        for item in expected:
            if not (self.root / item).exists():
                missing.append(item)

        return sorted(set(missing))

    def _build_recommendations(
        self,
        files: Iterable[Path],
        stack: List[str],
        missing: List[str],
    ) -> List[Recommendation]:
        relative = {path.relative_to(self.root).as_posix() for path in files}
        items: List[Recommendation] = []

        if "Laravel" in stack and ".github/workflows" in " ".join(missing + list(relative)):
            pass

        if not any(item.startswith(".github/workflows/") for item in relative):
            items.append(
                Recommendation(
                    category="delivery",
                    title="Add CI workflow",
                    detail="Set up GitHub Actions for Composer install, frontend build, and Laravel tests.",
                    severity="high",
                )
            )

        if "scripts" in missing or "references" in missing:
            items.append(
                Recommendation(
                    category="tooling",
                    title="Add internal toolkit",
                    detail="Repository-specific scripts and references will make setup, audits, and onboarding repeatable.",
                    severity="medium",
                )
            )

        if "Livewire" not in stack and (self.root / "Alliago.id_visa_platform_development_plan.md").exists():
            items.append(
                Recommendation(
                    category="architecture",
                    title="Plan interactive app layer",
                    detail="The product plan expects client and admin workflows that fit Livewire or another server-driven UI layer.",
                    severity="medium",
                )
            )

        readme = self.root / "README.md"
        if readme.exists():
            content = readme.read_text(encoding="utf-8", errors="ignore")
            if "Laravel" in content and "visa" not in content.lower():
                items.append(
                    Recommendation(
                        category="documentation",
                        title="Replace default README",
                        detail="The current README still looks generic. Document the visa platform domain, setup, and roadmap.",
                        severity="medium",
                    )
                )

        test_files = [item for item in relative if item.startswith("tests/")]
        if len(test_files) <= 3:
            items.append(
                Recommendation(
                    category="quality",
                    title="Expand test coverage",
                    detail="Only the default Laravel example tests are present. Add route, component, and domain flow coverage.",
                    severity="high",
                )
            )

        if "Filament" in stack and not (self.root / "app/Filament").exists():
            items.append(
                Recommendation(
                    category="admin",
                    title="Prepare Filament app structure",
                    detail="Create a clear Filament resource and page layout before the admin dashboard grows.",
                    severity="medium",
                )
            )

        return items


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Analyze project structure and suggest improvements.")
    parser.add_argument("target_path", nargs="?", default=".", help="Project directory to analyze")
    parser.add_argument("--json", action="store_true", help="Print JSON output")
    parser.add_argument("--output", help="Write the report to a file")
    parser.add_argument("--verbose", action="store_true", help="Enable verbose mode")
    return parser


def main() -> int:
    parser = build_parser()
    args = parser.parse_args()

    scaffolder = ProjectScaffolder(args.target_path, verbose=args.verbose)
    results = scaffolder.analyze()

    if args.json:
        output = json.dumps(results, indent=2)
    else:
        output = scaffolder.generate_report(results)

    if args.output:
        Path(args.output).write_text(output + "\n", encoding="utf-8")
    else:
        print(output)

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
