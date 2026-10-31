<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Owner (contoh: Nashywa)
        User::create([
            'name' => 'Owner Sicasa',
            'email' => 'owner@sicasa.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // Akun Kasir
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@sicasa.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}