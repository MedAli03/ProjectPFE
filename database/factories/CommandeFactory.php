<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\Tarif;
use App\Models\Commande;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Commande::class;

    public function definition()
    {
        return [
            'client_id' => User::where('role', 'client')->pluck('id')->random(),
            'pressing_id' => User::where('role', 'pressing')->pluck('id')->random(),
            'tarif_id' => Tarif::all()->random()->id,
            'status' => $this->faker->randomElement(['en attente', 'en cours', 'terminée']),
            'quantity' => $this->faker->numberBetween(1, 10),

        ];
    }
}
