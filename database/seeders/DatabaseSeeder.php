<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Call the AdminUserSeeder to add the admin user
        $this->call(AdminUserSeeder::class);
    }
}
