import type { Metadata } from "next";
import ProjectsGrid from "@/components/ProjectsGrid";
import { getProjects } from "@/lib/api";

export const metadata: Metadata = {
  title: "Projects",
  description: "Web, mobile, and API projects built by Kerod Ginbaru.",
};

export default async function ProjectsPage() {
  const projects = await getProjects();

  return (
    <section className="px-6 py-20 md:px-10">
      <div className="mx-auto max-w-7xl">
        <p className="font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">Archive</p>
        <h1 className="font-display mt-6 text-5xl font-extrabold uppercase md:text-7xl">Projects</h1>
        <p className="mt-4 max-w-xl text-[var(--ink)]/70">
          Completed builds, work in progress, and planned projects — filter by
          category or status below.
        </p>

        <div className="mt-14">
          <ProjectsGrid projects={projects} />
        </div>
      </div>
    </section>
  );
}
