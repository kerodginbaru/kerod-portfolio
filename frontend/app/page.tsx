import Hero from "@/components/Hero";
import BusinessTechSection from "@/components/BusinessTechSection";
import FeaturedProjects from "@/components/FeaturedProjects";
import ServicesSection from "@/components/ServicesSection";
import SkillsSection from "@/components/SkillsSection";
import ContactSection from "@/components/ContactSection";
import {
  getFeaturedProjects,
  getServices,
  getSiteSettings,
  getSkillCategories,
} from "@/lib/api";

export default async function Home() {
  const [settings, featuredProjects, services, skillCategories] = await Promise.all([
    getSiteSettings(),
    getFeaturedProjects(),
    getServices(),
    getSkillCategories(),
  ]);

  return (
    <>
      <Hero settings={settings} />
      <BusinessTechSection />
      <FeaturedProjects projects={featuredProjects} />
      <ServicesSection services={services} />
      <SkillsSection categories={skillCategories} />
      <ContactSection settings={settings} />
    </>
  );
}
