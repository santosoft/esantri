<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Muhaffizh>
 */
class MuhaffizhFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_induk' => strtoupper(fake()->unique()->bothify('?#####')),
            // 'nama' => fake()->name(),
            'alamat' => fake()->address(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 year', '-25 year'),
            'no_hp' => fake()->numerify('08##########'),
            'pendidikan_terakhir' => ['D1','D3','S1','S2'][rand(0,3)],
            'mulai_bertugas' => fake()->dateTimeBetween('-7 year', '-6 month'),
            'aktif' => 1,
            'unit_id' => rand(1,4),
            'user_id' => User::factory()->role('Muhaffizh'),
            'nama' => fn (array $attributes) => User::find($attributes['user_id'])->name,
             // relasi cacat, cumi!#%@#!
            // 'group_id' => Group::factory(),
            // 'unit_id' => rand(1,4),
        ];
    }
}
