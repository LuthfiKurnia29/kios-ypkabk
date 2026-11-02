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
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@pedulikasihabk.org'],
            [
                'name' => 'Admin Kios Mandiri',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Regular User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Donatur Test',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            ImpactMetricSeeder::class,
        ]);
    }
}
