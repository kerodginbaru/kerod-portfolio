<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Business Systems', 'Mobile', 'API', 'Tooling'];

        foreach ($categories as $i => $name) {
            ProjectCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name), 'sort_order' => $i]
            );
        }
    }
}
