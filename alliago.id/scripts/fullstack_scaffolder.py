#!/usr/bin/env python3
"""Plan or create a lightweight fullstack structure for this Laravel repository."""

from __future__ import annotations

import argparse
import json
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Dict, List


@dataclass
class PlannedChange:
    path: str
    type: str
    reason: str


class FullstackScaffolder:
    def __init__(self, target_path: str, apply_changes: bool = False) -> None:
        self.root = Path(target_path).resolve()
        self.apply_changes = apply_changes

    def validate_target(self) -> None:
        if not self.root.exists():
            raise FileNotFoundError(f"Target path does not exist: {self.root}")
        if not self.root.is_dir():
            raise NotADirectoryError(f"Target path is not a directory: {self.root}")
        if not (self.root / "artisan").exists():
            raise RuntimeError("This scaffolder currently expects a Laravel repository with an artisan file.")

    def analyze(self) -> Dict[str, object]:
        self.validate_target()
        planned = self._build_plan()

        created: List[str] = []
        if self.apply_changes:
            created = self._apply_plan(planned)

        return {
            "status": "success",
            "target": str(self.root),
            "mode": "apply" if self.apply_changes else "plan",
            "planned_changes": [asdict(item) for item in planned],
            "created": created,
        }

    def generate_report(self, results: Dict[str, object]) -> str:
        lines = [
            f"Target: {results['target']}",
            f"Mode: {results['mode']}",
            "",
            "Planned Changes",
        ]

        changes = results["planned_changes"]
        if changes:
            for item in changes:
                lines.append(f"- [{item['type']}] {item['path']}: {item['reason']}")
        else:
            lines.append("- No changes needed")

        created = results["created"]
        if created:
            lines.extend(["", "Created"])
            for item in created:
                lines.append(f"- {item}")

        return "\n".join(lines)

    def _build_plan(self) -> List[PlannedChange]:
        plan: List[PlannedChange] = []
        directories = {
            "app/Domain": "Keep visa product and application rules separate from framework glue.",
            "app/Actions": "Group write-side workflows such as checkout, submission, and document handling.",
            "app/Data": "Collect DTO-style payload objects for cleaner controller and service boundaries.",
            "app/Support": "Hold shared helpers, enums, and infrastructure-adjacent support code.",
            "app/Livewire": "Prepare for richer client and admin interactions without moving away from Laravel.",
            "resources/views/pages": "Separate route-level pages from small reusable Blade components.",
            "resources/views/livewire": "Reserve a clear home for Livewire views when interactive flows are added.",
            "tests/Feature/Public": "Add public website coverage without mixing it into client/admin test flows.",
            "tests/Feature/Client": "Prepare focused tests for authenticated applicant journeys.",
            "tests/Feature/Admin": "Prepare focused tests for Filament or admin dashboard workflows.",
            "tests/Unit/Domain": "Keep business-rule tests fast and framework-light.",
            "docs/adr": "Store architecture decisions as the product surface expands.",
        }

        files = {
            "routes/client.php": "Dedicated client routes keep applicant flows isolated from the marketing site.",
            "routes/admin.php": "Dedicated admin routes avoid overloading `routes/web.php` as back-office features grow.",
        }

        for path, reason in directories.items():
            if not (self.root / path).exists():
                plan.append(PlannedChange(path=path, type="directory", reason=reason))

        for path, reason in files.items():
            if not (self.root / path).exists():
                plan.append(PlannedChange(path=path, type="file", reason=reason))

        return plan

    def _apply_plan(self, plan: List[PlannedChange]) -> List[str]:
        created: List[str] = []
        for item in plan:
            target = self.root / item.path
            if item.type == "directory":
                target.mkdir(parents=True, exist_ok=True)
                marker = target / ".gitkeep"
                if not marker.exists():
                    marker.write_text("", encoding="utf-8")
                created.append(item.path)
            elif item.type == "file":
                target.parent.mkdir(parents=True, exist_ok=True)
                if target.suffix == ".php" and not target.exists():
                    target.write_text(self._stub_php_file(target.name), encoding="utf-8")
                    created.append(item.path)
        return created

    def _stub_php_file(self, name: str) -> str:
        header = "<?php\n\n"
        if name == "client.php":
            body = "use Illuminate\\Support\\Facades\\Route;\n\nRoute::middleware(['web'])->group(function (): void {\n    // Client-area routes belong here.\n});\n"
        elif name == "admin.php":
            body = "use Illuminate\\Support\\Facades\\Route;\n\nRoute::middleware(['web'])->group(function (): void {\n    // Admin or back-office routes belong here.\n});\n"
        else:
            body = ""
        return header + body


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Plan or apply a lightweight Laravel fullstack structure.")
    parser.add_argument("target_path", nargs="?", default=".", help="Project directory to scaffold")
    parser.add_argument("--apply", action="store_true", help="Create the planned directories and stub files")
    parser.add_argument("--json", action="store_true", help="Print machine-readable JSON")
    parser.add_argument("--output", help="Write the report to a file")
    return parser


def main() -> int:
    parser = build_parser()
    args = parser.parse_args()

    scaffolder = FullstackScaffolder(args.target_path, apply_changes=args.apply)
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
