<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tahfizh>
 */
class TahfizhFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $juz = rand(1,25);
        $juz2 = rand($juz, 30);
        $hal = rand(1,20);
        $hal2 = rand($hal, 25);
        return [
            'santri_id' => Santri::factory(),
            'group_id' => Group::factory(),
            'unit_id' => fn (array $attributes) => Group::find($attributes['group_id'])->unit_id,
            'muhaffizh_id' => fn (array $attributes) => Group::find($attributes['group_id'])->muhaffizh_id,
            'tahun' => date('Y'),
            'bulan' => date('n') - rand(0,2),
            'pekan' => rand(1,4),
            'juz' => rand(1, $juz),
            'halaman' => rand(1,24),
            'posisi_terakhir' => "Juz $juz Hal.$hal",
            'total_tahfizh' => "$juz2 Juz $hal2 Hal."
        ];
    }
}
