// Shared domain types. These mirror the JSON shape returned by the Laravel
// API Resources in backend/app/Http/Resources, so the frontend and backend
// stay in sync as the admin panel starts producing real data.

export type ProjectStatus = "completed" | "in_development" | "planned" | "archived";

export interface Technology {
  id: number;
  name: string;
  slug: string;
  icon: string | null;
  category: string;
}

export interface ProjectCategory {
  id: number;
  name: string;
  slug: string;
}

export interface ProjectImage {
  id: number;
  url: string;
  alt_text: string | null;
  caption: string | null;
  is_cover: boolean;
  sort_order: number;
}

export interface Project {
  id: number;
  title: string;
  slug: string;
  short_description: string;
  description: string;
  problem: string | null;
  solution: string | null;
  category: ProjectCategory | null;
  status: ProjectStatus;
  featured: boolean;
  year: number | null;
  github_url: string | null;
  live_url: string | null;
  architecture: string | null;
  challenges: string | null;
  lessons_learned: string | null;
  technologies: Technology[];
  images: ProjectImage[];
  cover_image: string | null;
}

export interface Service {
  id: number;
  title: string;
  slug: string;
  description: string;
  icon: string | null;
  featured: boolean;
}

export interface SkillCategory {
  id: number;
  name: string;
  skills: Skill[];
}

export interface Skill {
  id: number;
  name: string;
  proficiency: "learning" | "comfortable" | "strong" | null;
}

export interface Experience {
  id: number;
  role: string;
  organization: string;
  location: string | null;
  start_date: string;
  end_date: string | null;
  is_current: boolean;
  description: string;
}

export interface Education {
  id: number;
  degree: string;
  institution: string;
  field: string | null;
  start_date: string;
  end_date: string | null;
  description: string | null;
}

export interface BlogPost {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  cover_image: string | null;
  published_at: string | null;
}

export interface Testimonial {
  id: number;
  name: string;
  role: string | null;
  company: string | null;
  message: string;
  image: string | null;
  featured: boolean;
}

export interface SocialLink {
  id: number;
  platform: string;
  url: string;
  icon: string | null;
  sort_order: number;
}

export interface SiteSettings {
  name: string;
  professional_title: string;
  email: string;
  phone: string;
  location: string;
  hero_heading: string;
  hero_description: string;
  about_text: string;
  contact_cta: string;
  resume_url: string | null;
  profile_photo_url: string | null;
  admin_avatar_url: string | null;
}

export interface ContactPayload {
  name: string;
  email: string;
  phone?: string;
  subject: string;
  message: string;
}

export interface ApiSuccess<T> {
  success: true;
  data: T;
  message: string;
}

export interface ApiError {
  success: false;
  message: string;
  errors?: Record<string, string[]>;
}
