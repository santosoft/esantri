<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['Manajemen','Muhaffizh','Santri','Walisantri'];
        return [
            'name' => $name = fake()->name(),
            'username' => strtolower(str_replace(' ', '', $name)),
            'email' => strtolower(str_replace(' ', '', $name)).'@example.com',
            // 'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'role' => $roles[rand(0,3)]
        ];
    }

    public function role(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
        ]);
    }

    public function gender(string $gender): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name = fake()->name($gender),
            'username' => strtolower(str_replace(' ', '', $name)),
            'email' => strtolower(str_replace(' ', '', $name)).'@example.com',
        ]);
    }
}
