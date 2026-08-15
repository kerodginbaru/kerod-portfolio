"use client";

import { useState } from "react";
import { motion } from "motion/react";
import MagneticButton from "./MagneticButton";
import type { SiteSettings } from "@/types";

const reveal = {
  hidden: { y: "110%" },
  show: (i: number) => ({
    y: 0,
    transition: { duration: 0.8, delay: 0.1 * i, ease: [0.16, 1, 0.3, 1] as const },
  }),
};

function initials(name: string) {
  return name
    .split(" ")
    .map((w) => w[0])
    .join("")
    .slice(0, 2)
    .toUpperCase();
}

export default function Hero({ settings }: { settings: SiteSettings }) {
  const [first, last] = settings.name.split(" ");
  const [photoFailed, setPhotoFailed] = useState(false);

  return (
    <section className="relative overflow-hidden px-6 pb-20 pt-16 md:px-10 md:pt-24">
      {/* trace line — signature element, echoes the business->deployment path */}
      <svg
        aria-hidden
        className="pointer-events-none absolute inset-0 h-full w-full opacity-[0.15]"
        viewBox="0 0 100 100"
        preserveAspectRatio="none"
      >
        <motion.path
          d="M -5 20 L 30 20 L 40 50 L 70 50 L 80 85 L 105 85"
          fill="none"
          stroke="var(--accent)"
          strokeWidth="0.2"
          strokeDasharray="1 1.5"
          initial={{ pathLength: 0 }}
          animate={{ pathLength: 1 }}
          transition={{ duration: 2.4, ease: "easeInOut" }}
        />
      </svg>

      <p className="font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
        {settings.location} — Available for freelance &amp; contract work
      </p>

      <div className="mt-6 grid gap-10 md:grid-cols-[1.3fr_1fr] md:items-center md:gap-6">
        <div>
          <h1 className="font-display text-[13vw] font-extrabold uppercase leading-[0.85] tracking-tight md:text-[6.2vw]">
            <span className="block overflow-hidden">
              <motion.span variants={reveal} custom={0} initial="hidden" animate="show" className="block">
                {first}
              </motion.span>
            </span>
            <span className="block overflow-hidden">
              <motion.span
                variants={reveal}
                custom={1}
                initial="hidden"
                animate="show"
                className="block text-[var(--accent)]"
              >
                {last}
              </motion.span>
            </span>
          </h1>

          <motion.p
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.6, duration: 0.6 }}
            className="mt-8 max-w-xl text-lg text-[var(--ink)]/85 md:text-xl"
          >
            {settings.hero_description}
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.75, duration: 0.6 }}
            className="mt-8 flex gap-4"
          >
            <MagneticButton href="/#contact">Start a project</MagneticButton>
            <MagneticButton href="/projects" variant="outline">
              View work
            </MagneticButton>
          </motion.div>
        </div>

        {/* Portrait slot — split layout matching the "developer photo next
            to the headline" reference. Falls back to an initials avatar
            until a real photo exists at /public/images/profile.jpg. */}
        <motion.div
          initial={{ opacity: 0, scale: 0.96 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ delay: 0.3, duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
          className="relative mx-auto aspect-[4/5] w-full max-w-sm justify-self-center md:justify-self-end"
        >
          <div className="absolute -inset-3 rounded-[2rem] bg-[radial-gradient(circle_at_50%_20%,color-mix(in_srgb,var(--accent)_35%,transparent),transparent_70%)]" />
          <div className="relative h-full w-full overflow-hidden rounded-[1.75rem] border border-[var(--accent)]/40 bg-white/[0.03] shadow-[0_0_60px_-15px_var(--accent)]">
            {!photoFailed && settings.profile_photo_url ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img
                src={settings.profile_photo_url}
                alt={settings.name}
                onError={() => setPhotoFailed(true)}
                className="h-full w-full object-cover"
              />
            ) : (
              <div className="flex h-full w-full flex-col items-center justify-center gap-3 bg-gradient-to-b from-white/[0.04] to-transparent">
                <span className="font-display text-6xl font-extrabold text-[var(--accent)]/70">
                  {initials(settings.name)}
                </span>
                <span className="font-mono text-xs text-[var(--muted)]">Photo coming soon</span>
              </div>
            )}
          </div>
        </motion.div>
      </div>

      <div className="mt-16 overflow-hidden border-y border-[var(--ink)]/10 py-4">
        <div className="marquee-track font-mono text-sm text-[var(--muted)]">
          {[...Array(2)].map((_, i) => (
            <div key={i} className="flex shrink-0 gap-10 pr-10">
              {["Laravel", "Next.js", "Flutter", "MySQL", "REST APIs", "TypeScript", "Firebase", "PHP"].map((t) => (
                <span key={t}>{t}</span>
              ))}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
