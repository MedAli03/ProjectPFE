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
        return [
            'name' => fake()->name(),
            'cin' => $this->generateUniqueCin(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(30),
        ];
    }

    private function generateUniqueCin()
    {
        $cin = mt_rand(10000000, 99999999);

        // Check if the generated CIN already exists in the database
        while (\App\Models\User::where('cin', $cin)->exists()) {
            $cin = mt_rand(10000000, 99999999);
        }

        return $cin;
    }
}
