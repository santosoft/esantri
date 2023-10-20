<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tahsin>
 */
class TahsinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lastPage = rand(0,24);
        return [
            'santri_id' => Santri::factory(),
            'group_id' => Group::factory(),
            'unit_id' => fn (array $attributes) => Group::find($attributes['group_id'])->unit_id,
            'muhaffizh_id' => fn (array $attributes) => Group::find($attributes['group_id'])->muhaffizh_id,
            'tahun' => date('Y'),
            'bulan' => date('n') - rand(0,2),
            'pekan' => rand(1,4),
            'level_santri' => rand(1,6),
            'capaian' => rand(1,24),
            'posisi_terakhir' => ['P','M'][rand(0,1)].rand(1,3)." $lastPage".(($lastPage==0)?'Selesai':' Halaman')
        ];
    }
}
