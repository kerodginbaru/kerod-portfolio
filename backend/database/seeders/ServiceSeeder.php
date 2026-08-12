<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['title' => 'Web Application Development', 'description' => 'Custom web applications built on Laravel and Next.js, from internal tools to customer-facing platforms.', 'icon' => 'globe', 'featured' => true],
            ['title' => 'Mobile Application Development', 'description' => 'Cross-platform mobile apps with Flutter, backed by REST APIs and offline-first data with Hive.', 'icon' => 'smartphone', 'featured' => true],
            ['title' => 'Business Management Systems', 'description' => 'Systems that model how your business actually operates — inventory, staff, records, workflows.', 'icon' => 'briefcase', 'featured' => true],
            ['title' => 'REST API Development', 'description' => 'Clean, documented, authenticated APIs designed to power web and mobile clients alike.', 'icon' => 'server', 'featured' => false],
            ['title' => 'Database Development', 'description' => 'Relational schema design with proper normalization, indexing, and migration discipline.', 'icon' => 'database', 'featured' => false],
            ['title' => 'System Analysis & Design', 'description' => 'Translating a business problem into requirements, architecture, and a build plan before writing code.', 'icon' => 'git-branch', 'featured' => false],
            ['title' => 'Existing System Improvement', 'description' => 'Auditing and improving systems already in production — performance, security, and maintainability.', 'icon' => 'wrench', 'featured' => false],
        ];

        foreach ($services as $i => $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['title'])],
                $service + ['slug' => Str::slug($service['title']), 'sort_order' => $i]
            );
        }
    }
}
