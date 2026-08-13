"use client";

import { createContext, useContext, useEffect, useState, type ReactNode } from "react";
import { useRouter } from "next/navigation";

interface AdminUser {
  id: number;
  name: string;
  email: string;
}

interface AuthContextValue {
  user: AdminUser | null;
  token: string | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<{ ok: boolean; message: string }>;
  logout: () => void;
}

const AuthContext = createContext<AuthContextValue | null>(null);

const TOKEN_KEY = "admin_token";
const API_URL = process.env.NEXT_PUBLIC_API_URL;

export function AdminAuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AdminUser | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    const stored = localStorage.getItem(TOKEN_KEY);
    if (!stored) {
      // eslint-disable-next-line react-hooks/set-state-in-effect -- no async gap needed: there's simply no session to restore
      setLoading(false);
      return;
    }
    setToken(stored);
    fetch(`${API_URL}/admin/me`, {
      headers: { Authorization: `Bearer ${stored}`, Accept: "application/json" },
    })
      .then((res) => {
        if (!res.ok) throw new Error("invalid session");
        return res.json();
      })
      .then((json) => setUser(json.data))
      .catch(() => {
        localStorage.removeItem(TOKEN_KEY);
        setToken(null);
      })
      .finally(() => setLoading(false));
  }, []);

  async function login(email: string, password: string) {
    try {
      const res = await fetch(`${API_URL}/admin/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify({ email, password }),
      });
      const json = await res.json();
      if (!res.ok || !json.success) {
        return { ok: false, message: json.message ?? "Login failed." };
      }
      localStorage.setItem(TOKEN_KEY, json.data.token);
      setToken(json.data.token);
      setUser(json.data.user);
      return { ok: true, message: "Logged in." };
    } catch {
      return { ok: false, message: "Couldn't reach the server." };
    }
  }

  function logout() {
    if (token) {
      fetch(`${API_URL}/admin/logout`, {
        method: "POST",
        headers: { Authorization: `Bearer ${token}`, Accept: "application/json" },
      }).catch(() => {});
    }
    localStorage.removeItem(TOKEN_KEY);
    setToken(null);
    setUser(null);
    router.push("/admin/login");
  }

  return (
    <AuthContext.Provider value={{ user, token, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAdminAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAdminAuth must be used inside AdminAuthProvider");
  return ctx;
}
