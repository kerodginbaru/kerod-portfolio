import type { ProjectStatus } from "@/types";

const config: Record<ProjectStatus, { label: string; className: string }> = {
  completed: {
    label: "Completed",
    className: "border-[var(--accent-2)]/60 text-[var(--accent-2)]",
  },
  in_development: {
    label: "In Development",
    className: "border-[var(--accent)]/60 text-[var(--accent)]",
  },
  planned: {
    label: "Planned",
    className: "border-[var(--muted)]/60 text-[var(--muted)]",
  },
  archived: {
    label: "Archived",
    className: "border-[var(--ink)]/20 text-[var(--ink)]/50",
  },
};

export default function StatusBadge({ status }: { status: ProjectStatus }) {
  const c = config[status];
  return (
    <span
      className={`inline-flex items-center rounded-full border px-3 py-1 font-mono text-xs uppercase tracking-wide ${c.className}`}
    >
      {c.label}
    </span>
  );
}
