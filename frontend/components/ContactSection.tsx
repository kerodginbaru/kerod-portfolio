import ContactForm from "./ContactForm";
import type { SiteSettings } from "@/types";

export default function ContactSection({ settings }: { settings: SiteSettings }) {
  return (
    <section id="contact" className="border-t border-[var(--ink)]/10 px-6 py-24 md:px-10">
      <div className="mx-auto grid max-w-7xl gap-12 md:grid-cols-[1fr_1.2fr]">
        <div>
          <p className="trace-line font-mono text-xs uppercase tracking-[0.3em] text-[var(--accent)]">
            Get in touch
          </p>
          <h2 className="font-display mt-8 text-4xl font-extrabold uppercase leading-[0.95] md:text-5xl">
            Let&apos;s build
            <br />
            something.
          </h2>
          <p className="mt-6 max-w-sm text-[var(--ink)]/70">{settings.contact_cta}</p>
          <p className="mt-8 font-mono text-sm text-[var(--muted)]">{settings.email}</p>
          <p className="font-mono text-sm text-[var(--muted)]">{settings.phone}</p>
        </div>

        <ContactForm />
      </div>
    </section>
  );
}
