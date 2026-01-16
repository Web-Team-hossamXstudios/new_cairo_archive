<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // System Users (Roles)
        // =====================

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@nca.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($superAdmin, 'assignRole')) {
            $superAdmin->assignRole('super-admin');
        }

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@nca.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );


        $this->command->info('Created system users, employee portal users, and 10 of each employee type (admin, permanent, daily) with roles.');
    }
}
