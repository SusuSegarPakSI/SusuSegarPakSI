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
        // Seed Manager Account
        User::firstOrCreate(
            ['email' => env('MANAJER_EMAIL', 'manajer@sususegarpaksi.com')],
            [
                'name' => 'Pak Si (Manajer)',
                'password' => Hash::make(env('MANAJER_PASSWORD', 'password')),
                'role' => 'manajer',
                'is_active' => true,
            ]
        );

        // Seed Default Admin Account for Local Development
        if (app()->environment('local')) {
            User::firstOrCreate(
                ['email' => 'admin@sususegarpaksi.com'],
                [
                    'name' => 'Admin Default',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );
        }
    }
}
