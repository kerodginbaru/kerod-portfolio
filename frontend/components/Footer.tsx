import { getSiteSettings, getSocialLinks } from "@/lib/api";

export default async function Footer() {
  const [settings, social] = await Promise.all([getSiteSettings(), getSocialLinks()]);

  return (
    <footer id="contact-info" className="border-t border-[var(--ink)]/10 px-6 py-14 md:px-10">
      <div className="mx-auto max-w-7xl">
        <div className="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
          <div>
            <p className="font-display text-2xl font-bold">{settings.name}</p>
            <p className="mt-1 font-mono text-sm text-[var(--muted)]">{settings.professional_title}</p>
          </div>

          <ul className="flex flex-wrap gap-x-8 gap-y-2 font-mono text-sm">
            {social.map((s) => (
              <li key={s.id}>
                <a
                  href={s.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="focus-ring text-[var(--ink)]/70 transition-colors hover:text-[var(--accent)]"
                >
                  {s.platform}
                </a>
              </li>
            ))}
          </ul>
        </div>

        <div className="mt-10 flex flex-col gap-2 border-t border-[var(--ink)]/10 pt-6 font-mono text-xs text-[var(--muted)] md:flex-row md:items-center md:justify-between">
          <p>
            © {new Date().getFullYear()} {settings.name}. Built with Next.js &amp; Laravel.
          </p>
          <p>{settings.location}</p>
        </div>
      </div>
    </footer>
  );
}
