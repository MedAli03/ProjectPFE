<?php

namespace Database\Seeders;

use App\Models\Facture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = Facture::factory()->count(10)->create();

    }
}
