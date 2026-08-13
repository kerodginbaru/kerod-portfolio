"use client";

import { useEffect, useState } from "react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";

interface DashboardData {
  projects_total: number;
  projects_featured: number;
  projects_by_status: Record<string, number>;
  messages_unread: number;
  blog_posts_published: number;
  blog_posts_draft: number;
  technologies_total: number;
  services_total: number;
}

export default function AdminDashboardPage() {
  const { token } = useAdminAuth();
  const [data, setData] = useState<DashboardData | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!token) return;
    adminApi.dashboard(token).then((res) => {
      if (res.ok) setData(res.data as unknown as DashboardData);
      else setError(res.message);
    });
  }, [token]);

  const cards = data
    ? [
        { label: "Total projects", value: data.projects_total },
        { label: "Featured", value: data.projects_featured },
        { label: "Unread messages", value: data.messages_unread },
        { label: "Technologies", value: data.technologies_total },
        { label: "Services", value: data.services_total },
        { label: "Published posts", value: data.blog_posts_published },
      ]
    : [];

  return (
    <div>
      <h1 className="font-display text-3xl font-bold">Dashboard</h1>

      {error && <p className="mt-4 text-sm text-red-400">{error}</p>}

      <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {cards.map((c) => (
          <div key={c.label} className="rounded-2xl border border-[var(--ink)]/10 p-6">
            <p className="font-mono text-xs uppercase tracking-wide text-[var(--muted)]">{c.label}</p>
            <p className="font-display mt-2 text-4xl font-bold">{c.value}</p>
          </div>
        ))}
      </div>

      {data && (
        <div className="mt-8 rounded-2xl border border-[var(--ink)]/10 p-6">
          <p className="font-mono text-xs uppercase tracking-wide text-[var(--muted)]">Projects by status</p>
          <div className="mt-4 flex flex-wrap gap-4">
            {Object.entries(data.projects_by_status).map(([status, count]) => (
              <div key={status} className="font-mono text-sm">
                <span className="text-[var(--accent)]">{count}</span>{" "}
                <span className="text-[var(--muted)]">{status}</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
