<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ammarmassoud46@gmail.com'], 
            [
                'name' => 'Admin Amasu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'profile_complete' => true,
            ]
        );
    }
}
