<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facture>
 */
class FactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commande_id' => Commande::pluck('id')->random(),
            'client_id' => User::where('role', 'client')->pluck('id')->random(),
            'pressing_id' => User::where('role', 'pressing')->pluck('id')->random(),
            'numero' => $this->faker->unique()->numerify('FAC######'),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'total' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
