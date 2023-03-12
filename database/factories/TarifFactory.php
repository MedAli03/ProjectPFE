<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\Tarif;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarif>
 */
class TarifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Tarif::class;

    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 10, 50),
            'id_service' => Service::pluck('id')->random(),
            'id_article' => Article::pluck('id')->random(),
            'id_pressing' => User::where('role', 'pressing')->pluck('id')->random()
        ];
    }
}
