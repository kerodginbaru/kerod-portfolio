"use client";

import { useEffect, useRef, useState } from "react";
import Image from "next/image";
import { Loader2, Upload } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";
import type { SiteSettings } from "@/types";

const fields: { key: keyof SiteSettings; label: string; multiline?: boolean }[] = [
  { key: "name", label: "Name" },
  { key: "professional_title", label: "Professional title" },
  { key: "email", label: "Email" },
  { key: "phone", label: "Phone" },
  { key: "location", label: "Location" },
  { key: "hero_heading", label: "Hero heading" },
  { key: "hero_description", label: "Hero description", multiline: true },
  { key: "about_text", label: "About text", multiline: true },
  { key: "contact_cta", label: "Contact call-to-action", multiline: true },
  { key: "resume_url", label: "Resume / CV URL" },
];

export default function AdminSettingsPage() {
  const { token } = useAdminAuth();
  const [settings, setSettings] = useState<SiteSettings | null>(null);
  const [saving, setSaving] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [message, setMessage] = useState<string | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (!token) return;
    adminApi.settings.get(token).then((res) => {
      if (res.ok) setSettings(res.data as unknown as SiteSettings);
    });
  }, [token]);

  function update(key: keyof SiteSettings, value: string) {
    setSettings((s) => (s ? { ...s, [key]: value } : s));
  }

  async function handleSave(e: React.FormEvent) {
    e.preventDefault();
    if (!token || !settings) return;
    setSaving(true);
    setMessage(null);
    const payload = Object.fromEntries(
      fields.map((f) => [f.key, (settings[f.key] as string) ?? ""])
    );
    const res = await adminApi.settings.update(token, payload);
    setSaving(false);
    setMessage(res.ok ? "Saved." : res.message);
  }

  async function handlePhotoChange(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file || !token) return;
    setUploading(true);
    const res = await adminApi.settings.uploadPhoto(token, file);
    setUploading(false);
    if (res.ok) {
      setSettings(res.data as unknown as SiteSettings);
    } else {
      alert(res.message);
    }
    if (fileInputRef.current) fileInputRef.current.value = "";
  }

  if (!settings) {
    return <Loader2 className="animate-spin text-[var(--accent)]" />;
  }

  const inputClass =
    "focus-ring mt-1 w-full rounded-lg border border-[var(--ink)]/15 bg-transparent px-3 py-2 focus:border-[var(--accent)]";
  const labelClass = "font-mono text-xs uppercase tracking-wide text-[var(--muted)]";

  return (
    <div>
      <h1 className="font-display text-3xl font-bold">Site settings</h1>

      {/* Profile photo */}
      <div className="mt-8 flex items-center gap-6">
        <div className="relative h-24 w-24 shrink-0 overflow-hidden rounded-full border border-[var(--accent)]/40 bg-white/5">
          {settings.profile_photo_url ? (
            <Image src={settings.profile_photo_url} alt="Profile" fill className="object-cover" />
          ) : (
            <div className="flex h-full w-full items-center justify-center font-mono text-xs text-[var(--muted)]">
              No photo
            </div>
          )}
        </div>
        <div>
          <input
            ref={fileInputRef}
            type="file"
            accept="image/jpeg,image/png,image/webp"
            onChange={handlePhotoChange}
            className="hidden"
            id="photo-upload"
          />
          <label
            htmlFor="photo-upload"
            className="focus-ring inline-flex cursor-pointer items-center gap-2 rounded-full border border-[var(--ink)]/20 px-4 py-2 font-mono text-sm hover:border-[var(--accent)]"
          >
            {uploading ? <Loader2 size={14} className="animate-spin" /> : <Upload size={14} />}
            {settings.profile_photo_url ? "Replace photo" : "Upload photo"}
          </label>
          <p className="mt-2 font-mono text-xs text-[var(--muted)]">JPEG, PNG, or WebP. Max 4MB.</p>
        </div>
      </div>

      {/* Text fields */}
      <form onSubmit={handleSave} className="mt-10 grid max-w-2xl gap-5">
        {fields.map((f) => (
          <div key={f.key}>
            <label className={labelClass}>{f.label}</label>
            {f.multiline ? (
              <textarea
                rows={3}
                value={(settings[f.key] as string) ?? ""}
                onChange={(e) => update(f.key, e.target.value)}
                className={inputClass}
              />
            ) : (
              <input
                value={(settings[f.key] as string) ?? ""}
                onChange={(e) => update(f.key, e.target.value)}
                className={inputClass}
              />
            )}
          </div>
        ))}

        <div className="flex items-center gap-4">
          <button
            type="submit"
            disabled={saving}
            className="focus-ring inline-flex w-fit items-center gap-2 rounded-full bg-[var(--accent)] px-6 py-3 font-mono text-sm font-medium text-[var(--bg)] disabled:opacity-50"
          >
            {saving && <Loader2 size={16} className="animate-spin" />}
            Save changes
          </button>
          {message && <p className="font-mono text-sm text-[var(--muted)]">{message}</p>}
        </div>
      </form>
    </div>
  );
}
