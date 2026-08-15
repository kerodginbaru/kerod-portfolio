// Authenticated client for the admin API. Every function takes the bearer
// token from useAdminAuth() and returns a consistent { ok, data?, message,
// errors? } shape so admin pages can handle success/validation/error
// uniformly.

const API_URL = process.env.NEXT_PUBLIC_API_URL;

interface Result<T> {
  ok: boolean;
  data?: T;
  message: string;
  errors?: Record<string, string[]>;
}

async function request<T>(
  path: string,
  token: string,
  options: RequestInit = {}
): Promise<Result<T>> {
  try {
    const res = await fetch(`${API_URL}${path}`, {
      ...options,
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
        ...(options.body && !(options.body instanceof FormData)
          ? { "Content-Type": "application/json" }
          : {}),
        ...options.headers,
      },
    });
    const json = await res.json().catch(() => null);
    if (!res.ok || !json?.success) {
      return {
        ok: false,
        message: json?.message ?? `Request failed (${res.status}).`,
        errors: json?.errors,
      };
    }
    return { ok: true, data: json.data, message: json.message };
  } catch {
    return { ok: false, message: "Couldn't reach the server." };
  }
}

export const adminApi = {
  dashboard: (token: string) => request<Record<string, unknown>>("/admin/dashboard", token),

  projects: {
    list: (token: string) => request<import("@/types").Project[]>("/admin/projects", token),
    create: (token: string, body: Record<string, unknown>) =>
      request("/admin/projects", token, { method: "POST", body: JSON.stringify(body) }),
    update: (token: string, id: number, body: Record<string, unknown>) =>
      request(`/admin/projects/${id}`, token, { method: "PUT", body: JSON.stringify(body) }),
    delete: (token: string, id: number) =>
      request(`/admin/projects/${id}`, token, { method: "DELETE" }),
    toggleFeatured: (token: string, id: number) =>
      request(`/admin/projects/${id}/toggle-featured`, token, { method: "PATCH" }),
  },

  technologies: {
    list: (token: string) => request<import("@/types").Technology[]>("/admin/technologies", token),
  },

  categories: {
    list: (token: string) => request<{ id: number; name: string; slug: string }[]>("/admin/project-categories", token),
  },

 messages: {
    list: (token: string, status?: string) =>
      request<import("@/types").ContactPayload[]>(
        `/admin/messages${status ? `?status=${status}` : ""}`,
        token
      ),
    show: (token: string, id: number) => request(`/admin/messages/${id}`, token),
    updateStatus: (token: string, id: number, status: string) =>
      request(`/admin/messages/${id}`, token, { method: "PUT", body: JSON.stringify({ status }) }),
    delete: (token: string, id: number) => request(`/admin/messages/${id}`, token, { method: "DELETE" }),
  },

  settings: {
    get: (token: string) => request<import("@/types").SiteSettings>("/admin/site-settings", token),
    update: (token: string, settings: Record<string, string | null>) =>
      request<import("@/types").SiteSettings>("/admin/site-settings", token, {
        method: "PUT",
        body: JSON.stringify({ settings }),
      }),
    uploadPhoto: (token: string, file: File) => {
      const form = new FormData();
      form.append("photo", file);
      return request<import("@/types").SiteSettings>("/admin/site-settings/photo", token, {
        method: "POST",
        body: form,
      });
    },
  },

  projectImages: {
    upload: (token: string, projectId: number, file: File, isCover: boolean) => {
      const form = new FormData();
      form.append("image", file);
      form.append("is_cover", isCover ? "1" : "0");
      return request<import("@/types").ProjectImage>(`/admin/projects/${projectId}/images`, token, {
        method: "POST",
        body: form,
      });
    },
    delete: (token: string, projectId: number, imageId: number) =>
      request(`/admin/projects/${projectId}/images/${imageId}`, token, { method: "DELETE" }),
    setCover: (token: string, projectId: number, imageId: number) =>
      request<import("@/types").ProjectImage>(`/admin/projects/${projectId}/images/${imageId}/cover`, token, {
        method: "PATCH",
      }),
  },
};
