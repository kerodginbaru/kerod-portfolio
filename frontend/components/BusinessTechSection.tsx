"use client";

import { motion } from "motion/react";

const steps = [
  "Business Problem",
  "Requirements",
  "System Analysis",
  "Database",
  "Backend",
  "Web / Mobile",
  "Deployment",
];

export default function BusinessTechSection() {
  return (
    <section id="about" className="border-t border-[var(--ink)]/10 px-6 py-24 md:px-10">
      <div className="mx-auto max-w-7xl">
        <div className="grid gap-14 md:grid-cols-2">
          <div>
            <p className="trace-line font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
              How I work
            </p>
            <h2 className="font-display mt-8 text-5xl font-extrabold uppercase leading-[0.95] md:text-6xl">
              Technology
              <br />
              <span className="text-[var(--accent)]">+</span> Business
            </h2>
           <p className="mt-6 max-w-md text-[var(--ink)]/75">
  My foundation spans both Information Technology and Management,
  studied at Wolkite University. That dual grounding shapes every
  project: before a line of code is written, I map the business
  process — the workflow, the decisions, the people involved — then
  engineer a system built to match it, not the other way around.
</p>
          </div>

          <ol className="flex flex-col">
            {steps.map((step, i) => (
              <motion.li
                key={step}
                initial={{ opacity: 0, x: 24 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true, margin: "-80px" }}
                transition={{ duration: 0.5, delay: i * 0.06 }}
                className="flex items-center gap-5 border-b border-[var(--ink)]/10 py-4 last:border-none"
              >
                <span className="font-mono text-sm text-[var(--muted)]">
                  {String(i + 1).padStart(2, "0")}
                </span>
                <span className="font-display text-xl font-semibold md:text-2xl">{step}</span>
              </motion.li>
            ))}
          </ol>
        </div>
      </div>
    </section>
  );
}
