"use client";

import { useRef, useState, type ReactNode } from "react";
import Link from "next/link";
import { motion } from "motion/react";

interface MagneticButtonProps {
  href: string;
  children: ReactNode;
  variant?: "solid" | "outline";
  external?: boolean;
}

export default function MagneticButton({
  href,
  children,
  variant = "solid",
  external = false,
}: MagneticButtonProps) {
  const ref = useRef<HTMLAnchorElement>(null);
  const [pos, setPos] = useState({ x: 0, y: 0 });

  function handleMove(e: React.MouseEvent<HTMLAnchorElement>) {
    const el = ref.current;
    if (!el) return;
    const rect = el.getBoundingClientRect();
    const relX = e.clientX - rect.left - rect.width / 2;
    const relY = e.clientY - rect.top - rect.height / 2;
    setPos({ x: relX * 0.3, y: relY * 0.3 });
  }

  const base =
    "focus-ring relative inline-flex items-center justify-center gap-2 rounded-full px-7 py-3 text-sm font-medium font-mono tracking-tight transition-colors";
  const styles =
    variant === "solid"
      ? "bg-[var(--accent)] text-[var(--bg)] hover:bg-[#dab668]"
      : "border border-[var(--ink)]/25 text-[var(--ink)] hover:border-[var(--accent)] hover:text-[var(--accent)]";

  const props = external ? { target: "_blank", rel: "noopener noreferrer" } : {};

  return (
    <motion.span
      animate={{ x: pos.x, y: pos.y }}
      transition={{ type: "spring", stiffness: 150, damping: 12, mass: 0.2 }}
      className="inline-block"
    >
      <Link
        ref={ref}
        href={href}
        onMouseMove={handleMove}
        onMouseLeave={() => setPos({ x: 0, y: 0 })}
        className={`${base} ${styles}`}
        {...props}
      >
        {children}
      </Link>
    </motion.span>
  );
}
