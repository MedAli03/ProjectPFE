<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create an admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create a client user
        User::create([
            'first_name' => 'Client',
            'last_name' => 'User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_active' => true,
            'address' => '123 Main St',
            'city' => 'Anytown',
            'country' => 'USA',
            'postal_code' => '12345'
        ]);

        // Create a pressing user
        User::create([
            'pressing_name' => 'Pressing',
            'tva' => '123456',
            'email' => 'pressing@example.com',
            'password' => Hash::make('password'),
            'role' => 'pressing',
            'is_active' => false,
            'address' => '456 Oak St',
            'city' => 'Anytown',
            'country' => 'USA',
            'postal_code' => '67890'
        ]);
    }
}
