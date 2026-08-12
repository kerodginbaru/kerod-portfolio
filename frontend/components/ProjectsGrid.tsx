"use client";

import { useMemo, useState } from "react";
import { AnimatePresence, motion } from "motion/react";
import ProjectCard from "./ProjectCard";
import type { Project, ProjectStatus } from "@/types";

const statusFilters: { label: string; value: ProjectStatus | "all" }[] = [
  { label: "All", value: "all" },
  { label: "Completed", value: "completed" },
  { label: "In Development", value: "in_development" },
  { label: "Planned", value: "planned" },
];

export default function ProjectsGrid({ projects }: { projects: Project[] }) {
  const [status, setStatus] = useState<ProjectStatus | "all">("all");
  const [category, setCategory] = useState<string>("all");

  const categories = useMemo(() => {
    const set = new Map<string, string>();
    projects.forEach((p) => p.category && set.set(p.category.slug, p.category.name));
    return [{ slug: "all", name: "All" }, ...Array.from(set, ([slug, name]) => ({ slug, name }))];
  }, [projects]);

  const filtered = projects.filter((p) => {
    const statusMatch = status === "all" || p.status === status;
    const categoryMatch = category === "all" || p.category?.slug === category;
    return statusMatch && categoryMatch;
  });

  return (
    <div>
      <div className="flex flex-col gap-4 border-b border-[var(--ink)]/10 pb-6 md:flex-row md:items-center md:justify-between">
        <div className="flex flex-wrap gap-2">
          {categories.map((c) => (
            <button
              key={c.slug}
              onClick={() => setCategory(c.slug)}
              className={`focus-ring rounded-full px-4 py-1.5 font-mono text-sm transition-colors ${
                category === c.slug
                  ? "bg-[var(--accent)] text-[var(--bg)]"
                  : "border border-[var(--ink)]/15 text-[var(--ink)]/70 hover:border-[var(--accent)]/50"
              }`}
            >
              {c.name}
            </button>
          ))}
        </div>

        <div className="flex flex-wrap gap-2">
          {statusFilters.map((s) => (
            <button
              key={s.value}
              onClick={() => setStatus(s.value)}
              className={`focus-ring rounded-full px-4 py-1.5 font-mono text-sm transition-colors ${
                status === s.value
                  ? "border border-[var(--accent)] text-[var(--accent)]"
                  : "border border-transparent text-[var(--ink)]/50 hover:text-[var(--ink)]"
              }`}
            >
              {s.label}
            </button>
          ))}
        </div>
      </div>

      <motion.div layout className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <AnimatePresence mode="popLayout">
          {filtered.map((project) => (
            <motion.div
              key={project.id}
              layout
              initial={{ opacity: 0, y: 12 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -12 }}
              transition={{ duration: 0.3 }}
            >
              <ProjectCard project={project} />
            </motion.div>
          ))}
        </AnimatePresence>
      </motion.div>

      {filtered.length === 0 && (
        <p className="mt-16 text-center font-mono text-sm text-[var(--muted)]">
          Nothing matches those filters yet.
        </p>
      )}
    </div>
  );
}
