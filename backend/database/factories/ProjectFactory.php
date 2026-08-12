<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'problem' => fake()->sentence(),
            'solution' => fake()->sentence(),
            'category_id' => null,
            'status' => fake()->randomElement(['completed', 'in_development', 'planned', 'archived']),
            'featured' => false,
            'year' => fake()->numberBetween(2020, 2026),
            'github_url' => null,
            'live_url' => null,
            'architecture' => fake()->sentence(),
            'challenges' => fake()->sentence(),
            'lessons_learned' => fake()->sentence(),
            'sort_order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['status' => 'archived']);
    }
}
