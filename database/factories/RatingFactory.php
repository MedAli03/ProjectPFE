<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::where('role', 'client')->pluck('id')->random(),
            'pressing_id' => User::where('role', 'pressing')->pluck('id')->random(),
            'value' =>$this->faker->numberBetween(1, 5),
        ];
    }
}
