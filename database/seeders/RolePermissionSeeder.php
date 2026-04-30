<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'view admin dashboard',
            'view owner dashboard',
            'view cashier dashboard',
            'view warehouse dashboard',

            // Users
            'manage users',

            // Master Data
            'manage products',
            'manage categories',
            'manage units',
            'manage suppliers',
            'manage warehouses',

            // Stock
            'view stock',
            'manage stock',
            'adjust stock',

            // Sales / POS
            'create sales',
            'view sales',
            'void sales',
            'refund sales',

            // Purchase
            'create purchases',
            'view purchases',
            'manage purchases',

            // Reports
            'view sales reports',
            'view stock reports',
            'view profit reports',

            // Settings
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $warehouse = Role::firstOrCreate(['name' => 'warehouse staff']);

        $admin->syncPermissions(Permission::all());

        $owner->syncPermissions([
            'view owner dashboard',
            'view stock',
            'view sales',
            'view purchases',
            'view sales reports',
            'view stock reports',
            'view profit reports',
        ]);

        $cashier->syncPermissions([
            'view cashier dashboard',
            'create sales',
            'view sales',
        ]);

        $warehouse->syncPermissions([
            'view warehouse dashboard',
            'manage products',
            'manage categories',
            'manage units',
            'manage suppliers',
            'manage warehouses',
            'view stock',
            'manage stock',
            'adjust stock',
            'create purchases',
            'view purchases',
            'manage purchases',
        ]);
    }
}