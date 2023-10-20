<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Santri>
 */
class SantriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jk = rand() % 2;
        $gender = ['male','female'][$jk];
        $kelamin = ['Laki','Perempuan'][$jk];
        return [
            'no_induk' => sprintf("%05d", fake()->unique()->numberBetween(128,98304)),
            // 'nama' => fake()->name($gender),
            'alamat' => fake()->address(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-21 year', '-5 year'),
            'gender' => $kelamin,
            'nama_ayah' => fake()->name('male'),
            'nama_ibu' => fake()->name('female'),
            'no_hp' => fake()->numerify('08##########'),
            'mulai_belajar' => fake()->dateTimeBetween('-1 year', '-2 week'),
            'angkatan_kelas' => rand(1, 7)." Banat",
            'grade' => ['A','B','C','D'][rand(0, 3)],
            'level_santri' => rand(1,6),
            'group_id' => Group::factory(),
            'muhaffizh_id' => fn (array $attributes) => Group::find($attributes['group_id'])->muhaffizh_id,
            'user_id' => User::factory()->role('Santri')->gender($gender),
            'nama' => fn (array $attributes) => User::find($attributes['user_id'])->name,
        ];
    }
}
