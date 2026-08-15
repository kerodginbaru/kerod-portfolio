"use client";

import { useEffect, useState, useCallback } from "react";
import { Trash2 } from "lucide-react";
import { useAdminAuth } from "@/lib/adminAuth";
import { adminApi } from "@/lib/adminApi";

interface ContactMessage {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  subject: string;
  message: string;
  status: "new" | "read" | "replied" | "archived";
  created_at: string;
}

export default function AdminMessagesPage() {
  const { token } = useAdminAuth();
  const [messages, setMessages] = useState<ContactMessage[]>([]);
  const [selected, setSelected] = useState<ContactMessage | null>(null);
  const [loading, setLoading] = useState(true);

  const load = useCallback(() => {
    if (!token) return;
    setLoading(true);
    adminApi.messages.list(token).then((res) => {
      if (res.ok) setMessages((res.data as unknown as ContactMessage[]) ?? []);
      setLoading(false);
    });
  }, [token]);

  useEffect(() => {
    // eslint-disable-next-line react-hooks/set-state-in-effect -- standard load-on-mount fetch pattern
    load();
  }, [load]);

  async function handleSelect(m: ContactMessage) {
    setSelected(m);
    if (m.status === "new" && token) {
      await adminApi.messages.updateStatus(token, m.id, "read");
      load();
    }
  }

  async function handleStatusChange(id: number, status: string) {
    if (!token) return;
    await adminApi.messages.updateStatus(token, id, status);
    load();
  }

  async function handleDelete(id: number) {
    if (!token || !confirm("Delete this message?")) return;
    await adminApi.messages.delete(token, id);
    setSelected(null);
    load();
  }

  return (
    <div>
      <h1 className="font-display text-3xl font-bold">Messages</h1>

      <div className="mt-6 grid gap-6 lg:grid-cols-[1fr_1.4fr]">
        <div className="divide-y divide-[var(--ink)]/10 border-t border-[var(--ink)]/10">
          {loading && <p className="py-6 font-mono text-sm text-[var(--muted)]">Loading…</p>}
          {!loading && messages.length === 0 && (
            <p className="py-6 font-mono text-sm text-[var(--muted)]">No messages yet.</p>
          )}
          {messages.map((m) => (
            <button
              key={m.id}
              onClick={() => handleSelect(m)}
              className={`focus-ring block w-full py-3 text-left ${selected?.id === m.id ? "opacity-100" : "opacity-80 hover:opacity-100"}`}
            >
              <div className="flex items-center justify-between gap-2">
                <p className={`truncate text-sm ${m.status === "new" ? "font-semibold" : ""}`}>{m.name}</p>
                {m.status === "new" && <span className="h-2 w-2 shrink-0 rounded-full bg-[var(--accent)]" />}
              </div>
              <p className="truncate text-xs text-[var(--muted)]">{m.subject}</p>
            </button>
          ))}
        </div>

        <div>
          {selected ? (
            <div className="rounded-2xl border border-[var(--ink)]/10 p-6">
              <div className="flex items-start justify-between gap-4">
                <div>
                  <p className="font-display text-lg font-semibold">{selected.subject}</p>
                  <p className="mt-1 font-mono text-xs text-[var(--muted)]">
                    {selected.name} · {selected.email}
                    {selected.phone ? ` · ${selected.phone}` : ""}
                  </p>
                </div>
                <button
                  onClick={() => handleDelete(selected.id)}
                  className="focus-ring rounded-full p-2 text-[var(--ink)]/60 hover:bg-red-500/10 hover:text-red-400"
                >
                  <Trash2 size={16} />
                </button>
              </div>

              <p className="mt-4 whitespace-pre-wrap text-sm text-[var(--ink)]/85">{selected.message}</p>

              <div className="mt-6 flex items-center gap-2">
                <span className="font-mono text-xs text-[var(--muted)]">Status:</span>
                {(["new", "read", "replied", "archived"] as const).map((s) => (
                  <button
                    key={s}
                    onClick={() => handleStatusChange(selected.id, s)}
                    className={`focus-ring rounded-full px-3 py-1 font-mono text-xs ${
                      selected.status === s
                        ? "bg-[var(--accent)] text-[var(--bg)]"
                        : "border border-[var(--ink)]/15 text-[var(--ink)]/70"
                    }`}
                  >
                    {s}
                  </button>
                ))}
              </div>
            </div>
          ) : (
            <p className="font-mono text-sm text-[var(--muted)]">Select a message to read it.</p>
          )}
        </div>
      </div>
    </div>
  );
}
