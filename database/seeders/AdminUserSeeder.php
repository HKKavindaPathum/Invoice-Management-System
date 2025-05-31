<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find the admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Create a role called 'Admin' if not exists
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Get all permissions
        $permissions = Permission::pluck('name')->toArray();

        // Assign all permissions to the Admin role
        $adminRole->syncPermissions($permissions);

        // Assign the Admin role to the admin user
        $admin->assignRole($adminRole);
    }
}
