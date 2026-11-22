<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System Administrator Account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@campus.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Security Guard Account
        User::create([
            'name' => 'Gate Security Guard',
            'email' => 'guard@campus.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'guard',
        ]);

        // Driver Account (Student/Faculty)
        User::create([
            'name' => 'Michaela Lantaca',
            'email' => 'driver@campus.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);
    }
}