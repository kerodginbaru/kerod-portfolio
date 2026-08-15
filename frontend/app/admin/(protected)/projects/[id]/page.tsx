"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { Loader2 } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import ProjectForm from "@/components/admin/ProjectForm";
import ProjectImageManager from "@/components/admin/ProjectImageManager";
import type { Project } from "@/types";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export default function EditProjectPage() {
  const { id } = useParams<{ id: string }>();
  const { token } = useAdminAuth();
  const [project, setProject] = useState<Project | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!token) return;
    fetch(`${API_URL}/admin/projects/${id}`, {
      headers: { Authorization: `Bearer ${token}`, Accept: "application/json" },
    })
      .then((res) => res.json())
      .then((json) => {
        if (json.success) setProject(json.data);
        else setError(json.message);
      })
      .catch(() => setError("Couldn't load this project."));
  }, [token, id]);

  if (error) return <p className="text-sm text-red-400">{error}</p>;
  if (!project) return <Loader2 className="animate-spin text-[var(--accent)]" />;

  return (
    <div>
      <h1 className="font-display text-3xl font-bold">Edit project</h1>
      <div className="mt-8 max-w-2xl">
        <ProjectImageManager projectId={project.id} initialImages={project.images} />
      </div>
      <div className="mt-10">
        <ProjectForm project={project} />
      </div>
    </div>
  );
}
