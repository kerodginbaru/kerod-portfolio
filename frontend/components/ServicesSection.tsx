import type { Service } from "@/types";

export default function ServicesSection({ services }: { services: Service[] }) {
  return (
    <section id="services" className="border-t border-[var(--ink)]/10 px-6 py-24 md:px-10">
      <div className="mx-auto max-w-7xl">
        <p className="trace-line font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
          What I do
        </p>
        <h2 className="font-display mt-8 text-4xl font-extrabold uppercase md:text-5xl">Services</h2>

        <ul className="mt-12 divide-y divide-[var(--ink)]/10 border-t border-[var(--ink)]/10">
          {services.map((service, i) => (
            <li
              key={service.id}
              className="group flex flex-col gap-2 py-6 transition-colors hover:bg-white/[0.02] md:flex-row md:items-baseline md:gap-8 md:px-4"
            >
              <span className="font-mono text-sm text-[var(--muted)] md:w-12">
                {String(i + 1).padStart(2, "0")}
              </span>
              <h3 className="font-display text-2xl font-semibold md:w-72">{service.title}</h3>
              <p className="max-w-xl text-sm text-[var(--ink)]/65">{service.description}</p>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
