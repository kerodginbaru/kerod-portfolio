<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            ['name' => 'Flutter', 'category' => 'Framework'],
            ['name' => 'Laravel', 'category' => 'Framework'],
            ['name' => 'Next.js', 'category' => 'Framework'],
            ['name' => 'Tailwind CSS', 'category' => 'Framework'],
            ['name' => 'Bootstrap', 'category' => 'Framework'],
            ['name' => 'Riverpod', 'category' => 'Framework'],
            ['name' => 'PHP', 'category' => 'Language'],
            ['name' => 'Dart', 'category' => 'Language'],
            ['name' => 'JavaScript', 'category' => 'Language'],
            ['name' => 'TypeScript', 'category' => 'Language'],
            ['name' => 'Python', 'category' => 'Language'],
            ['name' => 'Java', 'category' => 'Language'],
            ['name' => 'SQL', 'category' => 'Language'],
            ['name' => 'MySQL', 'category' => 'Database'],
            ['name' => 'PostgreSQL', 'category' => 'Database'],
            ['name' => 'Firebase', 'category' => 'Database'],
            ['name' => 'Hive', 'category' => 'Database'],
            ['name' => 'REST API', 'category' => 'Backend'],
            ['name' => 'JWT', 'category' => 'Backend'],
            ['name' => 'Git', 'category' => 'Tool'],
            ['name' => 'GitHub', 'category' => 'Tool'],
        ];

        foreach ($technologies as $tech) {
            Technology::updateOrCreate(
                ['slug' => Str::slug($tech['name'])],
                $tech + ['slug' => Str::slug($tech['name'])]
            );
        }
    }
}
