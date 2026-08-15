"use client";

import { useEffect, useState, useCallback } from "react";
import Link from "next/link";
import { Plus, Star, Trash2, Pencil } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";
import StatusBadge from "@/components/StatusBadge";
import type { Project } from "@/types";

export default function AdminProjectsPage() {
  const { token } = useAdminAuth();
  const [projects, setProjects] = useState<Project[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const load = useCallback(() => {
    if (!token) return;
    setLoading(true);
   adminApi.projects.list(token).then((res) => {
      if (res.ok) setProjects((res.data as unknown as Project[]) ?? []);
      else setError(res.message);
      setLoading(false);
    });
  }, [token]);

  useEffect(() => {
    // eslint-disable-next-line react-hooks/set-state-in-effect -- standard load-on-mount fetch pattern
    load();
  }, [load]);

  async function handleDelete(id: number) {
    if (!token) return;
    if (!confirm("Delete this project? It can be restored later from the database if needed.")) return;
    const res = await adminApi.projects.delete(token, id);
    if (res.ok) load();
    else alert(res.message);
  }

  async function handleToggleFeatured(id: number) {
    if (!token) return;
    const res = await adminApi.projects.toggleFeatured(token, id);
    if (res.ok) load();
    else alert(res.message);
  }

  return (
    <div>
      <div className="flex items-center justify-between">
        <h1 className="font-display text-3xl font-bold">Projects</h1>
        <Link
          href="/admin/projects/create"
          className="focus-ring inline-flex items-center gap-2 rounded-full bg-[var(--accent)] px-5 py-2.5 font-mono text-sm text-[var(--bg)]"
        >
          <Plus size={16} /> New project
        </Link>
      </div>

      {error && <p className="mt-4 text-sm text-red-400">{error}</p>}
      {loading && <p className="mt-6 font-mono text-sm text-[var(--muted)]">Loading…</p>}

      <div className="mt-6 divide-y divide-[var(--ink)]/10 border-t border-[var(--ink)]/10">
        {projects.map((p) => (
          <div key={p.id} className="flex items-center justify-between gap-4 py-4">
            <div className="min-w-0">
              <div className="flex items-center gap-3">
                <p className="font-display truncate text-lg font-semibold">{p.title}</p>
                <StatusBadge status={p.status} />
              </div>
              <p className="mt-1 truncate text-sm text-[var(--muted)]">{p.short_description}</p>
            </div>

            <div className="flex shrink-0 items-center gap-2">
              <button
                onClick={() => handleToggleFeatured(p.id)}
                title={p.featured ? "Unfeature" : "Feature"}
                className={`focus-ring rounded-full p-2 hover:bg-white/5 ${p.featured ? "text-[var(--accent)]" : "text-[var(--muted)]"}`}
              >
                <Star size={16} fill={p.featured ? "currentColor" : "none"} />
              </button>
              <Link
                href={`/admin/projects/${p.id}`}
                className="focus-ring rounded-full p-2 text-[var(--ink)]/70 hover:bg-white/5"
              >
                <Pencil size={16} />
              </Link>
              <button
                onClick={() => handleDelete(p.id)}
                className="focus-ring rounded-full p-2 text-[var(--ink)]/70 hover:bg-red-500/10 hover:text-red-400"
              >
                <Trash2 size={16} />
              </button>
            </div>
          </div>
        ))}

        {!loading && projects.length === 0 && (
          <p className="py-10 text-center font-mono text-sm text-[var(--muted)]">No projects yet.</p>
        )}
      </div>
    </div>
  );
}
