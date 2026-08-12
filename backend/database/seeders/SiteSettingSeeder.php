<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'name' => 'Kerod Ginbaru',
            'professional_title' => 'Full-Stack & Mobile Developer',
            'email' => 'kerodhope@gmail.com',
            'phone' => '0965201930',
            'location' => 'Ethiopia',
            'hero_heading' => 'I build software that solves real business problems.',
            'hero_description' => 'Full-stack and mobile developer with a background in Information Technology and Management — I design systems that fit how a business actually runs, not just how the code compiles.',
            'about_text' => "My background spans both Information Technology and Management. That combination means I don't just implement features — I understand the business process behind the request, from requirements through to deployment.",
            'contact_cta' => "Have a project in mind? Let's talk about what you're building.",
            'resume_url' => null,
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }
    }
}
