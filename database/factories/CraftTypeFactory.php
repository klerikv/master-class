<?php

namespace Database\Factories;

use App\Models\CraftType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CraftTypeFactory extends Factory
{
    protected $model = CraftType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->paragraph(),
            'photo' => null,
        ];
    }
}