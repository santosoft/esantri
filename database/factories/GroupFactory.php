<?php

namespace Database\Factories;

use App\Models\Muhaffizh;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->lastName(),
            'unit_id' => rand(1,4),
            'muhaffizh_id' => Muhaffizh::factory(),
        ];
    }
}
