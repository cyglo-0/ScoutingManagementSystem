<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default System Admin
        User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@scout.ph',
            'phone'    => '09123456789',
            'age'      => 30,
            'school'   => null,
            'role'     => 'admin',
            'status'   => 'approved',
            'password' => Hash::make('password123'),
        ]);
    }
}