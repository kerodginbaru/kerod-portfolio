"use client";

import { useEffect, useState } from "react";
import { useRouter, usePathname } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import { LayoutDashboard, FolderKanban, Mail, LogOut, Loader2, Settings } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { getSiteSettings } from "@/lib/api";

const links = [
  { href: "/admin/dashboard", label: "Dashboard", icon: LayoutDashboard },
  { href: "/admin/projects", label: "Projects", icon: FolderKanban },
  { href: "/admin/messages", label: "Messages", icon: Mail },
  { href: "/admin/settings", label: "Settings", icon: Settings },
];

export default function AdminProtectedLayout({ children }: { children: React.ReactNode }) {
  const { user, loading, logout } = useAdminAuth();
  const router = useRouter();
  const pathname = usePathname();
  const [avatarUrl, setAvatarUrl] = useState<string | null>(null);

  useEffect(() => {
    if (!loading && !user) router.replace("/admin/login");
  }, [loading, user, router]);

  useEffect(() => {
    getSiteSettings().then((s) => setAvatarUrl(s.admin_avatar_url));
  }, []);

  if (loading || !user) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <Loader2 className="animate-spin text-[var(--accent)]" />
      </div>
    );
  }

  return (
    <div className="flex min-h-screen">
      <aside className="w-60 shrink-0 border-r border-[var(--ink)]/10 p-6">
        <div className="flex items-center gap-3">
          <div className="relative h-10 w-10 shrink-0 overflow-hidden rounded-full border border-[var(--accent)]/40 bg-white/5">
            {avatarUrl ? (
              <Image src={avatarUrl} alt="" fill className="object-cover" />
            ) : (
              <div className="flex h-full w-full items-center justify-center font-mono text-[10px] text-[var(--muted)]">
                KG
              </div>
            )}
          </div>
          <div className="min-w-0">
            <p className="font-display truncate text-sm font-bold">
              KG<span className="text-[var(--accent)]">.</span> Admin
            </p>
            <p className="truncate font-mono text-xs text-[var(--muted)]">{user.email}</p>
          </div>
        </div>

        <nav className="mt-8 flex flex-col gap-1">
          {links.map(({ href, label, icon: Icon }) => {
            const active = pathname.startsWith(href);
            return (
              <Link
                key={href}
                href={href}
                className={`focus-ring flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-sm transition-colors ${
                  active ? "bg-[var(--accent)]/15 text-[var(--accent)]" : "text-[var(--ink)]/75 hover:bg-white/5"
                }`}
              >
                <Icon size={16} />
                {label}
              </Link>
            );
          })}
        </nav>

        <button
          onClick={logout}
          className="focus-ring mt-8 flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-sm text-[var(--ink)]/60 hover:bg-white/5 hover:text-red-400"
        >
          <LogOut size={16} />
          Log out
        </button>
      </aside>

      <main className="flex-1 p-8">{children}</main>
    </div>
  );
}
