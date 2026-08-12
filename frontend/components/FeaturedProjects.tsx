import Link from "next/link";
import ProjectCard from "./ProjectCard";
import type { Project } from "@/types";

export default function FeaturedProjects({ projects }: { projects: Project[] }) {
  return (
    <section id="work" className="border-t border-[var(--ink)]/10 px-6 py-24 md:px-10">
      <div className="mx-auto max-w-7xl">
        <div className="flex items-end justify-between gap-6">
          <div>
            <p className="trace-line font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
              Selected work
            </p>
            <h2 className="font-display mt-8 text-4xl font-extrabold uppercase md:text-5xl">
              Featured Projects
            </h2>
          </div>
          <Link
            href="/projects"
            className="focus-ring hidden font-mono text-sm text-[var(--ink)]/70 underline decoration-[var(--accent)]/50 underline-offset-4 hover:text-[var(--accent)] md:inline-block"
          >
            View all projects
          </Link>
        </div>

        <div className="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
          {projects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>

        <Link
          href="/projects"
          className="focus-ring mt-8 inline-block font-mono text-sm text-[var(--accent)] underline underline-offset-4 md:hidden"
        >
          View all projects
        </Link>
      </div>
    </section>
  );
}
