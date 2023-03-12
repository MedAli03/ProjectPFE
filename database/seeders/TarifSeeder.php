<?php

namespace Database\Seeders;

use App\Models\Tarif;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tariffs = Tarif::factory()->count(10)->create();
    }
}
