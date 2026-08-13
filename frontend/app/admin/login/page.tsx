"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { Loader2 } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";

export default function AdminLoginPage() {
  const { login } = useAdminAuth();
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    const res = await login(email, password);
    setSubmitting(false);
    if (res.ok) {
      router.push("/admin/dashboard");
    } else {
      setError(res.message);
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center px-6">
      <div className="w-full max-w-sm">
        <p className="font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">Admin</p>
        <h1 className="font-display mt-3 text-3xl font-bold">Sign in</h1>

        <form onSubmit={handleSubmit} className="mt-8 grid gap-5">
          <div>
            <label className="font-mono text-xs uppercase tracking-wide text-[var(--muted)]">Email</label>
            <input
              type="email"
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="focus-ring mt-1 w-full border-b border-[var(--ink)]/20 bg-transparent py-2 focus:border-[var(--accent)]"
            />
          </div>
          <div>
            <label className="font-mono text-xs uppercase tracking-wide text-[var(--muted)]">Password</label>
            <input
              type="password"
              required
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="focus-ring mt-1 w-full border-b border-[var(--ink)]/20 bg-transparent py-2 focus:border-[var(--accent)]"
            />
          </div>

          {error && <p className="text-sm text-red-400">{error}</p>}

          <button
            type="submit"
            disabled={submitting}
            className="focus-ring mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-[var(--accent)] px-6 py-3 font-mono text-sm font-medium text-[var(--bg)] disabled:opacity-50"
          >
            {submitting && <Loader2 size={16} className="animate-spin" />}
            Sign in
          </button>
        </form>
      </div>
    </div>
  );
}
