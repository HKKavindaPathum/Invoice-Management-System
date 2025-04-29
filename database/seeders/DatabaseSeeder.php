<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class, // Create all permissions first
            AdminUserSeeder::class,  // Then create admin user and assign permissions
        ]);
    }
}
