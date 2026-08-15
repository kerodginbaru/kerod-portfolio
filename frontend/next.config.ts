import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  images: {
    remotePatterns: [
      // Uploaded photos (profile + project images) are served from
      // wherever NEXT_PUBLIC_API_URL points. onrender.com covers Render;
      // add your real production API host here too if you move hosts.
      { protocol: "https", hostname: "**.onrender.com" },
      { protocol: "http", hostname: "localhost" },
      { protocol: "http", hostname: "127.0.0.1" },
    ],
  },
};

export default nextConfig;
