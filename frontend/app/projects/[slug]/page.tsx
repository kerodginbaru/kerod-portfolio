import type { Metadata } from "next";
import { notFound } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import { ArrowLeft, ExternalLink, FolderGit2 } from "lucide-react";
import StatusBadge from "@/components/StatusBadge";
import { getProject, getProjects } from "@/lib/api";

export async function generateStaticParams() {
  const projects = await getProjects();
  return projects.map((p) => ({ slug: p.slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const project = await getProject(slug);
  if (!project) return {};
  return {
    title: project.title,
    description: project.short_description,
  };
}

const caseStudyFields: { key: keyof NonNullable<Awaited<ReturnType<typeof getProject>>>; label: string }[] = [
  { key: "problem", label: "Problem" },
  { key: "solution", label: "Solution" },
  { key: "architecture", label: "Architecture" },
  { key: "challenges", label: "Challenges" },
  { key: "lessons_learned", label: "Lessons Learned" },
];

export default async function ProjectDetailPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const project = await getProject(slug);
  if (!project) notFound();

  return (
    <article className="px-6 py-20 md:px-10">
      <div className="mx-auto max-w-4xl">
        <Link
          href="/projects"
          className="focus-ring inline-flex items-center gap-2 font-mono text-sm text-[var(--muted)] hover:text-[var(--accent)]"
        >
          <ArrowLeft size={16} /> All projects
        </Link>

        <div className="mt-8 flex flex-wrap items-center gap-3">
          <StatusBadge status={project.status} />
          {project.year && <span className="font-mono text-sm text-[var(--muted)]">{project.year}</span>}
        </div>

        <h1 className="font-display mt-4 text-5xl font-extrabold md:text-6xl">{project.title}</h1>
        <p className="mt-4 max-w-2xl text-lg text-[var(--ink)]/75">{project.description}</p>

        {project.cover_image && (
          <div className="relative mt-8 aspect-[16/9] w-full overflow-hidden rounded-2xl border border-[var(--ink)]/10">
            <Image src={project.cover_image} alt={project.title} fill className="object-cover" priority />
          </div>
        )}

        {project.images.length > 1 && (
          <div className="mt-3 grid grid-cols-4 gap-3">
            {project.images
              .filter((img) => img.url !== project.cover_image)
              .map((img) => (
                <div key={img.id} className="relative aspect-square overflow-hidden rounded-lg border border-[var(--ink)]/10">
                  <Image src={img.url} alt={img.alt_text ?? project.title} fill className="object-cover" />
                </div>
              ))}
          </div>
        )}

        <div className="mt-8 flex flex-wrap gap-3">
          {project.github_url ? (
            <a
              href={project.github_url}
              target="_blank"
              rel="noopener noreferrer"
              className="focus-ring inline-flex items-center gap-2 rounded-full border border-[var(--ink)]/20 px-5 py-2 font-mono text-sm hover:border-[var(--accent)]"
            >
              <FolderGit2 size={16} /> Source
            </a>
          ) : (
            <span className="inline-flex items-center gap-2 rounded-full border border-[var(--ink)]/10 px-5 py-2 font-mono text-sm text-[var(--muted)]">
              <FolderGit2 size={16} /> Source not public
            </span>
          )}
          {project.live_url ? (
            <a
              href={project.live_url}
              target="_blank"
              rel="noopener noreferrer"
              className="focus-ring inline-flex items-center gap-2 rounded-full bg-[var(--accent)] px-5 py-2 font-mono text-sm text-[var(--bg)]"
            >
              <ExternalLink size={16} /> Live demo
            </a>
          ) : (
            <span className="inline-flex items-center gap-2 rounded-full border border-[var(--ink)]/10 px-5 py-2 font-mono text-sm text-[var(--muted)]">
              <ExternalLink size={16} /> No live demo yet
            </span>
          )}
        </div>

        <div className="mt-10 flex flex-wrap gap-2">
          {project.technologies.map((t) => (
            <span key={t.id} className="rounded-full bg-white/5 px-3 py-1 font-mono text-xs text-[var(--muted)]">
              {t.name}
            </span>
          ))}
        </div>

        <div className="mt-16 space-y-10 border-t border-[var(--ink)]/10 pt-10">
          {caseStudyFields.map(
            (field) =>
              project[field.key] && (
                <div key={field.key}>
                  <h2 className="font-mono text-xs uppercase tracking-[0.25em] text-[var(--accent)]">
                    {field.label}
                  </h2>
                  <p className="mt-3 max-w-2xl text-[var(--ink)]/80">{project[field.key] as string}</p>
                </div>
              )
          )}
        </div>
      </div>
    </article>
  );
}
