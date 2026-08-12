import type { SkillCategory } from "@/types";

export default function SkillsSection({ categories }: { categories: SkillCategory[] }) {
  return (
    <section className="border-t border-[var(--ink)]/10 px-6 py-24 md:px-10">
      <div className="mx-auto max-w-7xl">
        <p className="trace-line font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
          Toolbox
        </p>
        <h2 className="font-display mt-8 text-4xl font-extrabold uppercase md:text-5xl">Skills</h2>

        <div className="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
          {categories.map((cat) => (
            <div key={cat.id}>
              <h3 className="font-mono text-sm uppercase tracking-wide text-[var(--muted)]">{cat.name}</h3>
              <ul className="mt-4 flex flex-wrap gap-2">
                {cat.skills.map((skill) => (
                  <li
                    key={skill.id}
                    className="rounded-full border border-[var(--ink)]/12 px-3 py-1.5 text-sm text-[var(--ink)]/85"
                  >
                    {skill.name}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
