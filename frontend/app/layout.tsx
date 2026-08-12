import type { Metadata } from "next";
import "./globals.css";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";

// NOTE: this build environment has no network access to
// fonts.googleapis.com, so the type system falls back to a curated
// system-font stack defined in globals.css (--font-display / --font-body
// / --font-mono). On a machine with normal internet access you can swap
// these for next/font/google, e.g.:
//
//   import { Bricolage_Grotesque, Inter, IBM_Plex_Mono } from "next/font/google";
//   const display = Bricolage_Grotesque({ variable: "--font-display", subsets: ["latin"], weight: ["500","700","800"] });
//   const body = Inter({ variable: "--font-body", subsets: ["latin"] });
//   const mono = IBM_Plex_Mono({ variable: "--font-mono", subsets: ["latin"], weight: ["400","500"] });
//
// then add `${display.variable} ${body.variable} ${mono.variable}` to the
// <body> className below. See frontend/README.md.

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://kerodginbaru.dev";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "Kerod Ginbaru | Full-Stack & Mobile Developer",
    template: "%s | Kerod Ginbaru",
  },
  description:
    "Portfolio of Kerod Ginbaru, a Full-Stack & Mobile Developer building web applications, mobile apps, business management systems, and REST APIs.",
  openGraph: {
    title: "Kerod Ginbaru | Full-Stack & Mobile Developer",
    description:
      "Portfolio of Kerod Ginbaru, a Full-Stack & Mobile Developer building web applications, mobile apps, business management systems, and REST APIs.",
    url: siteUrl,
    siteName: "Kerod Ginbaru",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title: "Kerod Ginbaru | Full-Stack & Mobile Developer",
    description:
      "Portfolio of Kerod Ginbaru, a Full-Stack & Mobile Developer building web applications, mobile apps, business management systems, and REST APIs.",
  },
  robots: { index: true, follow: true },
  alternates: { canonical: "/" },
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body className="antialiased">
        <Navbar />
        <main>{children}</main>
        <Footer />
      </body>
    </html>
  );
}
