<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'vendor-list',
            'vendor-create',
            'vendor-edit',
            'vendor-delete',
            'quotation-list',
            'quotation-create',
            'quotation-edit',
            'quotation-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
