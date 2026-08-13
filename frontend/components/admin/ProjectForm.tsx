"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { Loader2 } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";
import type { Project, ProjectStatus, Technology } from "@/types";

type FormState = {
  title: string;
  slug: string;
  short_description: string;
  description: string;
  problem: string;
  solution: string;
  status: ProjectStatus;
  featured: boolean;
  year: string;
  github_url: string;
  live_url: string;
  architecture: string;
  challenges: string;
  lessons_learned: string;
  technology_ids: number[];
};

const empty: FormState = {
  title: "",
  slug: "",
  short_description: "",
  description: "",
  problem: "",
  solution: "",
  status: "planned",
  featured: false,
  year: "",
  github_url: "",
  live_url: "",
  architecture: "",
  challenges: "",
  lessons_learned: "",
  technology_ids: [],
};

function toFormState(p: Project): FormState {
  return {
    title: p.title,
    slug: p.slug,
    short_description: p.short_description,
    description: p.description,
    problem: p.problem ?? "",
    solution: p.solution ?? "",
    status: p.status,
    featured: p.featured,
    year: p.year ? String(p.year) : "",
    github_url: p.github_url ?? "",
    live_url: p.live_url ?? "",
    architecture: p.architecture ?? "",
    challenges: p.challenges ?? "",
    lessons_learned: p.lessons_learned ?? "",
    technology_ids: p.technologies.map((t) => t.id),
  };
}

export default function ProjectForm({ project }: { project?: Project }) {
  const { token } = useAdminAuth();
  const router = useRouter();
  const [form, setForm] = useState<FormState>(project ? toFormState(project) : empty);
  const [technologies, setTechnologies] = useState<Technology[]>([]);
  const [saving, setSaving] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    if (!token) return;
    adminApi.technologies.list(token).then((res) => {
      if (res.ok) setTechnologies((res.data as unknown as Technology[]) ?? []);
    });
  }, [token]);

  function update<K extends keyof FormState>(key: K, value: FormState[K]) {
    setForm((f) => ({ ...f, [key]: value }));
  }

  function toggleTech(id: number) {
    setForm((f) => ({
      ...f,
      technology_ids: f.technology_ids.includes(id)
        ? f.technology_ids.filter((t) => t !== id)
        : [...f.technology_ids, id],
    }));
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (!token) return;
    setSaving(true);
    setErrors({});

    const payload = {
      ...form,
      year: form.year ? Number(form.year) : null,
      problem: form.problem || null,
      solution: form.solution || null,
      github_url: form.github_url || null,
      live_url: form.live_url || null,
      architecture: form.architecture || null,
      challenges: form.challenges || null,
      lessons_learned: form.lessons_learned || null,
    };

    const res = project
      ? await adminApi.projects.update(token, project.id, payload)
      : await adminApi.projects.create(token, payload);

    setSaving(false);

    if (res.ok) {
      router.push("/admin/projects");
    } else {
      setErrors(res.errors ?? {});
      alert(res.message);
    }
  }

  const inputClass =
    "focus-ring w-full rounded-lg border border-[var(--ink)]/15 bg-transparent px-3 py-2 focus:border-[var(--accent)]";
  const labelClass = "font-mono text-xs uppercase tracking-wide text-[var(--muted)]";

  function fieldError(name: string) {
    return errors[name]?.[0];
  }

  return (
    <form onSubmit={handleSubmit} className="grid max-w-2xl gap-6">
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className={labelClass}>Title</label>
          <input
            required
            value={form.title}
            onChange={(e) => update("title", e.target.value)}
            className={`mt-1 ${inputClass}`}
          />
          {fieldError("title") && <p className="mt-1 text-xs text-red-400">{fieldError("title")}</p>}
        </div>
        <div>
          <label className={labelClass}>Slug</label>
          <input
            required
            value={form.slug}
            onChange={(e) => update("slug", e.target.value)}
            className={`mt-1 ${inputClass}`}
          />
          {fieldError("slug") && <p className="mt-1 text-xs text-red-400">{fieldError("slug")}</p>}
        </div>
      </div>

      <div>
        <label className={labelClass}>Short description</label>
        <input
          required
          value={form.short_description}
          onChange={(e) => update("short_description", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>

      <div>
        <label className={labelClass}>Description</label>
        <textarea
          required
          rows={3}
          value={form.description}
          onChange={(e) => update("description", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>

      <div className="grid gap-4 sm:grid-cols-3">
        <div>
          <label className={labelClass}>Status</label>
          <select
            value={form.status}
            onChange={(e) => update("status", e.target.value as ProjectStatus)}
            className={`mt-1 ${inputClass}`}
          >
            <option value="planned">Planned</option>
            <option value="in_development">In development</option>
            <option value="completed">Completed</option>
            <option value="archived">Archived</option>
          </select>
        </div>
        <div>
          <label className={labelClass}>Year</label>
          <input
            type="number"
            value={form.year}
            onChange={(e) => update("year", e.target.value)}
            className={`mt-1 ${inputClass}`}
          />
        </div>
        <div className="flex items-end pb-2">
          <label className="flex items-center gap-2 font-mono text-sm">
            <input
              type="checkbox"
              checked={form.featured}
              onChange={(e) => update("featured", e.target.checked)}
            />
            Featured
          </label>
        </div>
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className={labelClass}>GitHub URL</label>
          <input
            value={form.github_url}
            onChange={(e) => update("github_url", e.target.value)}
            className={`mt-1 ${inputClass}`}
            placeholder="https://github.com/..."
          />
        </div>
        <div>
          <label className={labelClass}>Live demo URL</label>
          <input
            value={form.live_url}
            onChange={(e) => update("live_url", e.target.value)}
            className={`mt-1 ${inputClass}`}
            placeholder="https://..."
          />
        </div>
      </div>

      <div>
        <label className={labelClass}>Problem</label>
        <textarea
          rows={2}
          value={form.problem}
          onChange={(e) => update("problem", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>
      <div>
        <label className={labelClass}>Solution</label>
        <textarea
          rows={2}
          value={form.solution}
          onChange={(e) => update("solution", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>
      <div>
        <label className={labelClass}>Architecture</label>
        <textarea
          rows={2}
          value={form.architecture}
          onChange={(e) => update("architecture", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>
      <div>
        <label className={labelClass}>Challenges</label>
        <textarea
          rows={2}
          value={form.challenges}
          onChange={(e) => update("challenges", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>
      <div>
        <label className={labelClass}>Lessons learned</label>
        <textarea
          rows={2}
          value={form.lessons_learned}
          onChange={(e) => update("lessons_learned", e.target.value)}
          className={`mt-1 ${inputClass}`}
        />
      </div>

      <div>
        <label className={labelClass}>Technologies</label>
        <div className="mt-2 flex flex-wrap gap-2">
          {technologies.map((t) => (
            <button
              type="button"
              key={t.id}
              onClick={() => toggleTech(t.id)}
              className={`focus-ring rounded-full border px-3 py-1 font-mono text-xs transition-colors ${
                form.technology_ids.includes(t.id)
                  ? "border-[var(--accent)] bg-[var(--accent)]/15 text-[var(--accent)]"
                  : "border-[var(--ink)]/15 text-[var(--ink)]/70"
              }`}
            >
              {t.name}
            </button>
          ))}
        </div>
      </div>

      <button
        type="submit"
        disabled={saving}
        className="focus-ring mt-2 inline-flex w-fit items-center gap-2 rounded-full bg-[var(--accent)] px-6 py-3 font-mono text-sm font-medium text-[var(--bg)] disabled:opacity-50"
      >
        {saving && <Loader2 size={16} className="animate-spin" />}
        {project ? "Save changes" : "Create project"}
      </button>
    </form>
  );
}
