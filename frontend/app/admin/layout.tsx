import { AdminAuthProvider } from "@/lib/adminAuth";

export default function AdminRootLayout({ children }: { children: React.ReactNode }) {
  return (
    <AdminAuthProvider>
      <div className="min-h-screen bg-[var(--bg)] text-[var(--ink)]">{children}</div>
    </AdminAuthProvider>
  );
}
