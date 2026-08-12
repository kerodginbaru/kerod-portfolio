<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            SocialLinkSeeder::class,
            ServiceSeeder::class,
            TechnologySeeder::class,
            ProjectCategorySeeder::class,
            SkillSeeder::class,
            ProjectSeeder::class,
            // TestimonialSeeder intentionally not called — testimonials
            // are entered only through the admin panel, never seeded.
        ]);
    }
}
