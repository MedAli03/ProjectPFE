<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = Price::factory()->count(10)->create();
        
    }
}
