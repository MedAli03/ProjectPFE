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
            'email' => 'admin' . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8) . '@example.com',
            'phone' => rand(1000000000, 9999999999),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_validated' => true
        ]);

        // Create a client user
        User::create([
            'first_name' => 'Client',
            'last_name' => 'User',
            'email' =>  'client' . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8) . '@example.com',
            'phone' => rand(1000000000, 9999999999),
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_active' => true,
            'address' => '123 Main St',
            'city' => 'Anytown',
            'country' => 'USA',
            'postal_code' => '12345',
            'is_validated' => true
        ]);

        // Create a pressing user
        User::create([
            'pressing_name' => 'Pressing',
            'tva' => '1234562',
            'email' =>  'pressing ' . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8) . '@example.com',
            'phone' => rand(1000000000, 9999999999),
            'password' => Hash::make('password'),
            'role' => 'pressing',
            'is_active' => false,
            'address' => '456 Oak St',
            'city' => 'Anytown',
            'country' => 'USA',
            'postal_code' => '67890',
            'is_validated' => false
        ]);
    }
}
