<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Pekan;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absen>
 */
class AbsenFactory extends Factory
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
            'pekan_id' => Pekan::factory(),
            'hadir' => $h = rand(7,12),
            'izin' => $i = (($x = 12-$h-rand(0,2)) < 0) ? 0 : $x,
            'sakit' => $s = (($x = 12-$h-$i-rand(0,2)) < 0) ? 0 : $x,
            'alpha' => $a = (($x = 12-$h-$i-$s-rand(0,2)) < 0) ? 0 : $x,
        ];
    }
}
