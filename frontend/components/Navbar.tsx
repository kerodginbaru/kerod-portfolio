"use client";

import Link from "next/link";
import { useState } from "react";
import { motion } from "motion/react";
import { Menu, X } from "lucide-react";

const links = [
  { href: "/#work", label: "Work" },
  { href: "/#services", label: "Services" },
  { href: "/#about", label: "About" },
  { href: "/#contact", label: "Contact" },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);

  return (
    <header className="sticky top-0 z-50 border-b border-[var(--ink)]/10 bg-[var(--bg)]/80 backdrop-blur">
      <nav className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 md:px-10">
        <Link href="/" className="font-display text-lg font-bold tracking-tight focus-ring">
          KG<span className="text-[var(--accent)]">.</span>
        </Link>

        <ul className="hidden gap-8 font-mono text-sm text-[var(--ink)]/80 md:flex">
          {links.map((l) => (
            <li key={l.href}>
              <Link href={l.href} className="focus-ring transition-colors hover:text-[var(--accent)]">
                {l.label}
              </Link>
            </li>
          ))}
        </ul>

        <Link
          href="/#contact"
          className="focus-ring hidden rounded-full border border-[var(--accent)]/60 px-5 py-2 font-mono text-sm text-[var(--accent)] transition-colors hover:bg-[var(--accent)] hover:text-[var(--bg)] md:inline-flex"
        >
          Start a project
        </Link>

        <button
          aria-label="Toggle menu"
          className="focus-ring text-[var(--ink)] md:hidden"
          onClick={() => setOpen((v) => !v)}
        >
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>
      </nav>

      {open && (
        <motion.ul
          initial={{ height: 0, opacity: 0 }}
          animate={{ height: "auto", opacity: 1 }}
          exit={{ height: 0, opacity: 0 }}
          className="flex flex-col gap-1 border-t border-[var(--ink)]/10 px-6 pb-4 font-mono text-sm md:hidden"
        >
          {links.map((l) => (
            <li key={l.href}>
              <Link href={l.href} onClick={() => setOpen(false)} className="focus-ring block py-3">
                {l.label}
              </Link>
            </li>
          ))}
        </motion.ul>
      )}
    </header>
  );
}
