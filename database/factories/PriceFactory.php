<?php

namespace Database\Factories;

use App\Models\Tarif;
use App\Models\Commande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
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
            'tarif_id' => Tarif::pluck('id')->random(),
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
