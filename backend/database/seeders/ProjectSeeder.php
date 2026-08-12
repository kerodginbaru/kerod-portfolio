<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = fn (string $name) => ProjectCategory::where('slug', Str::slug($name))->value('id');
        $techIds = fn (array $names) => Technology::whereIn('slug', array_map(fn ($n) => Str::slug($n), $names))->pluck('id')->all();

        $projects = [
            [
                'title' => 'Yadot — ያዶት',
                'short_description' => 'Business Management System',
                'description' => "A business management system built to digitize day-to-day operations — records, workflows, and reporting — for a real operating business.",
                'problem' => 'Manual, paper-based processes made day-to-day operations slow and error-prone.',
                'solution' => "A Laravel-driven system that models the business's actual workflow, with role-based access and structured records.",
                'category' => 'Business Systems',
                'status' => 'completed',
                'featured' => true,
                'year' => 2024,
                'architecture' => 'Laravel MVC backend with Blade/Bootstrap admin views and a MySQL relational schema.',
                'challenges' => 'Modeling business rules accurately required close iteration with how the business actually operates day to day.',
                'lessons_learned' => 'Requirements gathered directly from real workflows produce far more durable systems than assumptions.',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Bootstrap', 'JavaScript'],
            ],
            [
                'title' => 'HOPE',
                'short_description' => 'AI Life Planner',
                'description' => 'A mobile life-planning app that helps users structure goals and daily plans, built with an offline-first architecture.',
                'problem' => 'Existing planning apps are either too rigid or require constant connectivity.',
                'solution' => 'A Flutter app with Riverpod state management and Hive local storage, syncing to Firebase when online.',
                'category' => 'Mobile',
                'status' => 'in_development',
                'featured' => true,
                'year' => 2025,
                'architecture' => 'Flutter client, Riverpod for state, Hive for offline persistence, Firebase for sync and auth.',
                'challenges' => 'Designing a clean offline-first sync strategy between Hive and Firebase.',
                'lessons_learned' => null,
                'technologies' => ['Flutter', 'Dart', 'Firebase', 'Riverpod', 'Hive'],
            ],
            [
                'title' => 'EthioShop',
                'short_description' => 'Mobile E-Commerce',
                'description' => 'A mobile e-commerce application for browsing and purchasing products, built with Flutter and Firebase.',
                'problem' => null,
                'solution' => null,
                'category' => 'Mobile',
                'status' => 'in_development',
                'featured' => true,
                'year' => 2025,
                'architecture' => 'Flutter client backed by Firebase for auth, data, and storage.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Flutter', 'Dart', 'Firebase'],
            ],
            [
                'title' => 'EOTC-RCAMS',
                'short_description' => 'Church Administrative REST API',
                'description' => 'A REST API powering administrative record-keeping for a church organization, with JWT-secured endpoints.',
                'problem' => null,
                'solution' => null,
                'category' => 'API',
                'status' => 'completed',
                'featured' => true,
                'year' => 2024,
                'architecture' => 'Laravel REST API with JWT authentication and a MySQL schema for administrative records.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'JWT'],
            ],
            [
                'title' => 'Document Management System',
                'short_description' => 'Administrative System',
                'description' => 'A system for organizing, storing, and retrieving administrative documents.',
                'problem' => null,
                'solution' => null,
                'category' => 'Business Systems',
                'status' => 'completed',
                'featured' => false,
                'year' => 2023,
                'architecture' => 'PHP backend with a MySQL schema for document metadata and retrieval.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['PHP', 'MySQL'],
            ],
            [
                'title' => 'Digital Restaurant Menu — ያዲ ክትፎ',
                'short_description' => 'Digital menu system',
                'description' => 'A digital menu system for a restaurant, replacing printed menus with a manageable digital equivalent.',
                'problem' => null,
                'solution' => null,
                'category' => 'Business Systems',
                'status' => 'completed',
                'featured' => false,
                'year' => 2023,
                'architecture' => 'PHP with a MySQL-backed menu structure.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['PHP', 'MySQL'],
            ],
            [
                'title' => 'BiblioVerse',
                'short_description' => 'Library management platform',
                'description' => "A Laravel-based platform for organizing and browsing a library's catalog.",
                'problem' => null,
                'solution' => null,
                'category' => 'Business Systems',
                'status' => 'completed',
                'featured' => false,
                'year' => 2024,
                'architecture' => 'Laravel backend, Tailwind CSS front views, MySQL database.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Tailwind CSS'],
            ],
            [
                'title' => 'HOPE System',
                'short_description' => 'Laravel-based management system',
                'description' => 'A Laravel and MySQL management system, distinct from the HOPE mobile app.',
                'problem' => null,
                'solution' => null,
                'category' => 'Business Systems',
                'status' => 'completed',
                'featured' => false,
                'year' => 2024,
                'architecture' => 'Laravel backend with a MySQL relational schema.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Laravel', 'MySQL'],
            ],
            [
                'title' => 'Employee Management & Attendance System',
                'short_description' => 'Planned: staff records and attendance tracking',
                'description' => 'A planned system for managing employee records and tracking attendance, spanning a Flutter client and Laravel API.',
                'problem' => null,
                'solution' => null,
                'category' => 'Business Systems',
                'status' => 'planned',
                'featured' => false,
                'year' => null,
                'architecture' => 'Planned: Flutter client, Laravel API, MySQL database.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Flutter', 'Laravel', 'MySQL'],
            ],
            [
                'title' => 'Data Structures & Algorithms Visualizer',
                'short_description' => 'Planned: interactive DSA visualizer',
                'description' => 'A planned interactive tool for visualizing data structures and algorithms in the browser.',
                'problem' => null,
                'solution' => null,
                'category' => 'Tooling',
                'status' => 'planned',
                'featured' => false,
                'year' => null,
                'architecture' => 'Planned: TypeScript/JavaScript, rendered in-browser.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['TypeScript', 'JavaScript'],
            ],
            [
                'title' => 'Network Monitoring System',
                'short_description' => 'Planned: network monitoring tool',
                'description' => 'A planned tool for monitoring network status and activity, built in Python.',
                'problem' => null,
                'solution' => null,
                'category' => 'Tooling',
                'status' => 'planned',
                'featured' => false,
                'year' => null,
                'architecture' => 'Planned: Python.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['Python'],
            ],
            [
                'title' => 'Advanced Database Project',
                'short_description' => 'Planned: advanced relational database project',
                'description' => 'A planned advanced database project exploring MySQL and PostgreSQL design patterns.',
                'problem' => null,
                'solution' => null,
                'category' => 'Tooling',
                'status' => 'planned',
                'featured' => false,
                'year' => null,
                'architecture' => 'Planned: MySQL / PostgreSQL.',
                'challenges' => null,
                'lessons_learned' => null,
                'technologies' => ['MySQL', 'PostgreSQL'],
            ],
        ];

        foreach ($projects as $i => $data) {
            $technologies = $data['technologies'];
            $category = $data['category'];
            unset($data['technologies'], $data['category']);

            $project = Project::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                $data + [
                    'slug' => Str::slug($data['title']),
                    'category_id' => $categoryId($category),
                    'sort_order' => $i,
                    // No github_url / live_url — these are placeholders
                    // until real links are added via the admin panel.
                    'github_url' => null,
                    'live_url' => null,
                ]
            );

            $project->technologies()->sync($techIds($technologies));
        }
    }
}
