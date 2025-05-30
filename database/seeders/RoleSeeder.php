<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Assign Admin role to the first user (you can change this to assign to a specific user)
        $user = User::first();
        if ($user) {
            $user->assignRole($adminRole);
        }
    }
} 