<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matan>
 */
class MatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'group_id' => Group::factory(),
            'unit_id' => fn (array $attributes) => Group::find($attributes['group_id'])->unit_id,
            'muhaffizh_id' => fn (array $attributes) => Group::find($attributes['group_id'])->muhaffizh_id,
            'tahun' => date('Y'),
            'bulan' => date('n') - rand(0,2),
            'pekan' => rand(1,4),
            'matan_jazari' => "Bait ".rand(1,15)
        ];
    }
}
