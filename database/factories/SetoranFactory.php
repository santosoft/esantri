<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Pekan;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setoran>
 */
class SetoranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mutqin_juz = rand(1, 30);
        $mutqin_hal = rand(1, 25);
        $tahfizh_juz = rand(1,25);
        $tahfizh_juz2 = rand($tahfizh_juz, 30);
        $tahfizh_hal = rand(1,20);
        $tahfizh_hal2 = rand($tahfizh_hal, 25);
        $lastPage = rand(0,24);
        if($lastPage == 0) $lastPage = 'Selesai';
        else $lastPage.= " Halaman";
        return [
            'pekan_id' => Pekan::factory(),
            'santri_id' => Santri::factory(),
            'group_id' => Group::factory(),
            'unit_id' => fn (array $attributes) => Group::find($attributes['group_id'])->unit_id,
            'muhaffizh_id' => fn (array $attributes) => Group::find($attributes['group_id'])->muhaffizh_id,
            'mutqin_halaman' => rand(1, 20),
            'total_mutqin' => "$mutqin_juz Juz $mutqin_hal Hal.",

            'tahfizh_juz' => rand(1, $tahfizh_juz),
            'tahfizh_halaman' => rand(1,24),
            'tahfizh_posisi_terakhir' => "Juz $tahfizh_juz Hal.$tahfizh_hal",
            'total_tahfizh' => "$tahfizh_juz2 Juz $tahfizh_hal2 Hal.",

            'level_santri' => rand(1,6),
            'tahsin_capaian' => rand(1,24),
            'tahsin_posisi_terakhir' => ['P','M'][rand(0,1)].rand(1,3)." $lastPage",

            'matan_jazari' => "Bait ".rand(1,15),

            'absen_hadir' => $h = rand(7,12),
            'absen_izin' => $i = (($x = 12-$h-rand(0,2)) < 0) ? 0 : $x,
            'absen_sakit' => $s = (($x = 12-$h-$i-rand(0,2)) < 0) ? 0 : $x,
            'absen_alpha' => (($x = 12-$h-$i-$s-rand(0,2)) < 0) ? 0 : $x,
        ];
    }
}
