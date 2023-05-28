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
        $status = $this->faker->randomElement(['payé', 'non payé']);

        return [
            'commande_id' => Commande::pluck('id')->random(),
            'client_id' => User::where('role', 'client')->pluck('id')->random(),
            'pressing_id' => User::where('role', 'pressing')->pluck('id')->random(),
            'status' => $status,
            'numero' => $this->faker->unique()->numerify('FAC######'),

        ];
    }
}
