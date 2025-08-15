<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",
            "category-list",
            "category-create",
            "category-edit",
            "category-delete",
            "product-list",
            "product-create",
            "product-edit",
            "product-delete",
            "client-list",
            "client-create",
            "client-edit",
            "client-delete",
            "invoice-list",
            "invoice-create",
            "invoice-edit",
            "invoice-delete",
            "invoice-download",
            "invoice-print",
            "settings",
        ];

        foreach($permissions as $key => $permission) {
            permission::create(['name' => $permission]);
        }
    }
}
