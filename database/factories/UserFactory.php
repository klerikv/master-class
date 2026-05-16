<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'phone' => '+7'.$this->faker->numerify('##########'),
            'role' => 'visitor',
            'photo' => null,
        ];
    }

    public function instructor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'instructor',
        ]);
    }

    public function visitor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'visitor',
        ]);
    }
}
