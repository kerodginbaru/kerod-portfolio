<?php

namespace Database\Seeders;

use App\Models\SkillCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Programming Languages' => ['Dart', 'PHP', 'JavaScript', 'TypeScript', 'Python', 'Java', 'SQL'],
            'Frameworks' => ['Flutter', 'Laravel', 'Next.js', 'Tailwind CSS', 'Bootstrap', 'Riverpod'],
            'Backend' => ['REST APIs', 'JWT', 'Authentication', 'Authorization', 'RBAC'],
            'Databases' => ['MySQL', 'PostgreSQL', 'Firebase', 'Hive'],
            'Tools' => ['Git', 'GitHub', 'VS Code', 'Postman', 'Composer'],
        ];

        $order = 0;
        foreach ($categories as $categoryName => $skills) {
            $category = SkillCategory::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'slug' => Str::slug($categoryName), 'sort_order' => $order++]
            );

            foreach ($skills as $i => $skillName) {
                $category->skills()->updateOrCreate(
                    ['name' => $skillName],
                    ['name' => $skillName, 'sort_order' => $i]
                );
            }
        }
    }
}
