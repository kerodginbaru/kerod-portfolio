import Link from "next/link";
import Image from "next/image";
import { ArrowUpRight } from "lucide-react";
import StatusBadge from "./StatusBadge";
import type { Project } from "@/types";

export default function ProjectCard({ project }: { project: Project }) {
  return (
    <Link
      href={`/projects/${project.slug}`}
      className="focus-ring group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-[var(--ink)]/10 bg-white/[0.02] transition-colors hover:border-[var(--accent)]/50"
    >
      {project.cover_image && (
        <div className="relative aspect-[16/9] w-full overflow-hidden">
          <Image
            src={project.cover_image}
            alt={project.title}
            fill
            sizes="(max-width: 768px) 100vw, 400px"
            className="object-cover transition-transform duration-500 group-hover:scale-105"
          />
        </div>
      )}

      <div className="flex flex-1 flex-col justify-between p-6">
        <div className="flex items-start justify-between gap-4">
          <StatusBadge status={project.status} />
          <ArrowUpRight
            size={20}
            className="text-[var(--muted)] transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-[var(--accent)]"
          />
        </div>

        <div className="mt-10">
          <h3 className="font-display text-2xl font-bold">{project.title}</h3>
          <p className="mt-2 text-sm text-[var(--ink)]/70">{project.short_description}</p>
        </div>

        <div className="mt-8 flex flex-wrap gap-2">
          {project.technologies.slice(0, 4).map((t) => (
            <span
              key={t.id}
              className="rounded-full bg-white/5 px-3 py-1 font-mono text-xs text-[var(--muted)]"
            >
              {t.name}
            </span>
          ))}
        </div>
      </div>
    </Link>
  );
}
