# Frontend — Next.js Portfolio

## Stack

Next.js (App Router) + TypeScript + Tailwind CSS 4 + Motion + React Hook
Form + Zod + lucide-react.

## Setup

```bash
npm install
cp .env.example .env.local
```

Set `NEXT_PUBLIC_API_URL` to the Laravel API's base URL including `/api`
(e.g. `http://localhost:8000/api`). If it's left empty, or the backend is
unreachable, every page transparently falls back to the demo data in
`lib/fallback-data.ts` — built entirely from Kerod's real projects, so the
site is fully browsable before the backend is deployed.

```bash
npm run dev
```

## Fonts

This repo currently ships with system-font-stack fallbacks defined in
`app/globals.css` (`--font-display` / `--font-body` / `--font-mono`),
because the sandbox this was generated in has no network access to
`fonts.googleapis.com`. On a normal machine, swap in the intended
Google Fonts by editing `app/layout.tsx`:

```ts
import { Bricolage_Grotesque, Inter, IBM_Plex_Mono } from "next/font/google";

const display = Bricolage_Grotesque({ variable: "--font-display", subsets: ["latin"], weight: ["500", "700", "800"] });
const body = Inter({ variable: "--font-body", subsets: ["latin"] });
const mono = IBM_Plex_Mono({ variable: "--font-mono", subsets: ["latin"], weight: ["400", "500"] });
```

then add `${display.variable} ${body.variable} ${mono.variable}` to the
`<body>` className.

## Structure

```
app/
  page.tsx                  — home (Hero, Business+Tech, Featured Projects, Services, Skills, Contact)
  projects/page.tsx          — filterable project archive
  projects/[slug]/page.tsx    — project case study
  sitemap.ts, robots.ts
components/                   — all UI split into focused, reusable pieces
lib/api.ts                    — typed fetch client with fallback-data.ts backup
types/index.ts                 — shared types mirroring the Laravel API resources
```

## Verified

`npm run build` and `npm run lint` both pass clean in this repo as
delivered (Next.js 16, static generation for all 12 seeded project pages).

## Deployment

Deploy to Vercel (recommended) or any Node host:

```bash
npm run build
npm start
```

Set `NEXT_PUBLIC_API_URL` and `NEXT_PUBLIC_SITE_URL` as environment
variables in your hosting provider.
