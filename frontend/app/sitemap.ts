import type { MetadataRoute } from "next";
import { getProjects } from "@/lib/api";

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://kerodginbaru.dev";
  const projects = await getProjects();

  return [
    { url: `${siteUrl}/`, changeFrequency: "monthly", priority: 1 },
    { url: `${siteUrl}/projects`, changeFrequency: "weekly", priority: 0.9 },
    ...projects.map((p) => ({
      url: `${siteUrl}/projects/${p.slug}`,
      changeFrequency: "monthly" as const,
      priority: 0.7,
    })),
  ];
}
