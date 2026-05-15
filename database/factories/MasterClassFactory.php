<?php

namespace Database\Factories;

use App\Models\CraftType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterClassFactory extends Factory
{
    protected $model = MasterClass::class;

    public function definition(): array
    {
        $timeSlots = ['9-11', '11-13', '13-15', '15-17'];
        $date = $this->faker->dateTimeBetween('+1 day', '+1 month');
        
        return [
            'craft_type_id' => CraftType::factory(),
            'instructor_id' => User::factory()->instructor(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => $date,
            'time_slot' => $this->faker->randomElement($timeSlots),
            'max_participants' => $this->faker->numberBetween(5, 30),
            'price' => $this->faker->numberBetween(500, 5000),
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    public function full(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_participants' => 0,
        ]);
    }
}