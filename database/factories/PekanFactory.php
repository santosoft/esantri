<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pekan>
 */
class PekanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $d = Carbon::parse(fake()->unique()->dateTimeBetween('-4 month'));
        $pekan = $d->format('W') - date('W', mktime(0,0,0,$d->format('n'),0,$d->format('Y')));
        return [
            'id'    => $d->format('Ym').$pekan,
            'tahun' => $d->format('Y'),
            'bulan' => $d->format('n'),
            'pekan' => $pekan,
            'tgl_awal'  => $d->startOfWeek()->format('Y-m-d'),
            'tgl_akhir' => $d->endOfWeek()->subDays(2)->format('Y-m-d'),
        ];
    }
}
