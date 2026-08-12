// Central API client. Every getter below tries the real Laravel API first
// (NEXT_PUBLIC_API_URL) and falls back to the local demo data in
// lib/fallback-data.ts if the backend isn't reachable yet or the request
// fails. This lets the frontend be developed and deployed independently of
// the backend, and switches over transparently once the API is live.

import type {
  ApiSuccess,
  BlogPost,
  ContactPayload,
  Education,
  Experience,
  Project,
  Service,
  SiteSettings,
  SkillCategory,
  SocialLink,
} from "@/types";
import * as fallback from "./fallback-data";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

async function get<T>(path: string): Promise<T | null> {
  if (!API_URL) return null;
  try {
    const res = await fetch(`${API_URL}${path}`, {
      next: { revalidate: 60 },
      headers: { Accept: "application/json" },
    });
    if (!res.ok) return null;
    const json = (await res.json()) as ApiSuccess<T>;
    return json.success ? json.data : null;
  } catch {
    // Backend not deployed yet, or unreachable — caller falls back to demo data.
    return null;
  }
}

export async function getProjects(): Promise<Project[]> {
  return (await get<Project[]>("/projects")) ?? fallback.projects;
}

export async function getFeaturedProjects(): Promise<Project[]> {
  const remote = await get<Project[]>("/projects/featured");
  return remote ?? fallback.projects.filter((p) => p.featured);
}

export async function getProject(slug: string): Promise<Project | null> {
  const remote = await get<Project>(`/projects/${slug}`);
  return remote ?? fallback.projects.find((p) => p.slug === slug) ?? null;
}

export async function getServices(): Promise<Service[]> {
  return (await get<Service[]>("/services")) ?? fallback.services;
}

export async function getSkillCategories(): Promise<SkillCategory[]> {
  return (await get<SkillCategory[]>("/skills")) ?? fallback.skillCategories;
}

export async function getExperience(): Promise<Experience[]> {
  return (await get<Experience[]>("/experience")) ?? fallback.experience;
}

export async function getEducation(): Promise<Education[]> {
  return (await get<Education[]>("/education")) ?? fallback.education;
}

export async function getSiteSettings(): Promise<SiteSettings> {
  return (await get<SiteSettings>("/site-settings")) ?? fallback.siteSettings;
}

export async function getSocialLinks(): Promise<SocialLink[]> {
  return (await get<SocialLink[]>("/social-links")) ?? fallback.socialLinks;
}

export async function getBlogPosts(): Promise<BlogPost[]> {
  return (await get<BlogPost[]>("/blog")) ?? [];
}

export async function submitContact(
  payload: ContactPayload
): Promise<{ ok: boolean; message: string; errors?: Record<string, string[]> }> {
  if (!API_URL) {
    return {
      ok: false,
      message: "The contact API isn't connected yet. Email kerodhope@gmail.com directly for now.",
    };
  }
  try {
    const res = await fetch(`${API_URL}/contact`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok || !json.success) {
      return { ok: false, message: json.message ?? "Something went wrong.", errors: json.errors };
    }
    return { ok: true, message: json.message ?? "Message sent." };
  } catch {
    return { ok: false, message: "Couldn't reach the server. Please try again shortly." };
  }
}
