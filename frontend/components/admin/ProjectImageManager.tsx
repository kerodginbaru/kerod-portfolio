"use client";

import { useRef, useState } from "react";
import Image from "next/image";
import { Star, Trash2, Upload, Loader2 } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";
import type { ProjectImage } from "@/types";

export default function ProjectImageManager({
  projectId,
  initialImages,
}: {
  projectId: number;
  initialImages: ProjectImage[];
}) {
  const { token } = useAdminAuth();
  const [images, setImages] = useState<ProjectImage[]>(initialImages);
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  async function handleUpload(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file || !token) return;
    setUploading(true);
    const res = await adminApi.projectImages.upload(token, projectId, file, images.length === 0);
    setUploading(false);
    if (res.ok) {
      setImages((prev) => [...prev, res.data as unknown as ProjectImage]);
    } else {
      alert(res.message);
    }
    if (fileInputRef.current) fileInputRef.current.value = "";
  }

  async function handleDelete(imageId: number) {
    if (!token || !confirm("Delete this image?")) return;
    const res = await adminApi.projectImages.delete(token, projectId, imageId);
    if (res.ok) {
      setImages((prev) => prev.filter((i) => i.id !== imageId));
    } else {
      alert(res.message);
    }
  }

  async function handleSetCover(imageId: number) {
    if (!token) return;
    const res = await adminApi.projectImages.setCover(token, projectId, imageId);
    if (res.ok) {
      setImages((prev) => prev.map((i) => ({ ...i, is_cover: i.id === imageId })));
    } else {
      alert(res.message);
    }
  }

  return (
    <div>
      <label className="font-mono text-xs uppercase tracking-wide text-[var(--muted)]">Images</label>

      <div className="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-4">
        {images.map((img) => (
          <div
            key={img.id}
            className="group relative aspect-square overflow-hidden rounded-lg border border-[var(--ink)]/15"
          >
            <Image src={img.url} alt={img.alt_text ?? ""} fill className="object-cover" />
            <div className="absolute inset-0 flex items-center justify-center gap-2 bg-black/60 opacity-0 transition-opacity group-hover:opacity-100">
              <button
                type="button"
                onClick={() => handleSetCover(img.id)}
                title={img.is_cover ? "Cover image" : "Set as cover"}
                className={`focus-ring rounded-full bg-white/10 p-2 ${img.is_cover ? "text-[var(--accent)]" : "text-white"}`}
              >
                <Star size={14} fill={img.is_cover ? "currentColor" : "none"} />
              </button>
              <button
                type="button"
                onClick={() => handleDelete(img.id)}
                className="focus-ring rounded-full bg-white/10 p-2 text-white hover:text-red-400"
              >
                <Trash2 size={14} />
              </button>
            </div>
            {img.is_cover && (
              <span className="absolute left-1 top-1 rounded-full bg-[var(--accent)] px-2 py-0.5 font-mono text-[10px] text-[var(--bg)]">
                Cover
              </span>
            )}
          </div>
        ))}

        <label
          htmlFor="project-image-upload"
          className="focus-ring flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-[var(--ink)]/20 text-[var(--muted)] hover:border-[var(--accent)] hover:text-[var(--accent)]"
        >
          {uploading ? <Loader2 size={18} className="animate-spin" /> : <Upload size={18} />}
          <span className="font-mono text-[10px]">Add image</span>
        </label>
        <input
          ref={fileInputRef}
          id="project-image-upload"
          type="file"
          accept="image/jpeg,image/png,image/webp"
          onChange={handleUpload}
          className="hidden"
        />
      </div>
      <p className="mt-2 font-mono text-xs text-[var(--muted)]">JPEG, PNG, or WebP. Max 4MB each.</p>
    </div>
  );
}
