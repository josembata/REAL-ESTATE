<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole('Admin');
            } elseif ($user->role === 'agent') {
                $user->assignRole('Agent');
            } elseif ($user->role === 'tenant') {
                $user->assignRole('Tenant');
            } elseif ($user->role === 'landlord') {
                $user->assignRole('Landlord');
            }
        }
    }
}
