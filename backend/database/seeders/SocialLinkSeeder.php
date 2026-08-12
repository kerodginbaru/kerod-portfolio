<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            // Placeholder URLs — replace with real profile URLs via the
            // admin panel (/admin/settings) once available.
            ['platform' => 'GitHub', 'url' => 'https://github.com/', 'icon' => 'github', 'sort_order' => 1],
            ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/', 'icon' => 'linkedin', 'sort_order' => 2],
            ['platform' => 'WhatsApp', 'url' => 'https://wa.me/251965201930', 'icon' => 'message-circle', 'sort_order' => 3],
            ['platform' => 'Email', 'url' => 'mailto:kerodhope@gmail.com', 'icon' => 'mail', 'sort_order' => 4],
        ];

        foreach ($links as $link) {
            SocialLink::updateOrCreate(['platform' => $link['platform']], $link);
        }
    }
}
