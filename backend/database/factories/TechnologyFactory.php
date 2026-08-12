<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Technology>
 */
class TechnologyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'icon' => null,
            'category' => fake()->randomElement(['Language', 'Framework', 'Database', 'Tool']),
            'description' => fake()->sentence(),
        ];
    }
}
