<?php

namespace Database\Seeders;

use App\Models\Commande;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commandes = Commande::factory()->count(10)->create();
       
    }
}
