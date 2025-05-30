<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
<<<<<<< HEAD
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
=======
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'admin_users',
            'show_users',
            'edit_users',
            'delete_users',
            'add_users',
            'assign_roles',
            'manage_orders',
            'view_orders',
            'update_order_status',
            'manage_products',
            'add_products',
            'edit_products',
            'delete_products',
            'add_customer_credit',
            'update_credit'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $employee = Role::create(['name' => 'Employee', 'guard_name' => 'web']);
        $employee->givePermissionTo([
            'show_users',
            'view_orders',
            'update_order_status',
            'add_customer_credit',
            'update_credit'
        ]);

        $marketingManager = Role::create(['name' => 'Marketing Manager', 'guard_name' => 'web']);
        $marketingManager->givePermissionTo([
            'show_users',
            'edit_users',
            'add_users',
            'manage_products',
            'add_products',
            'edit_products',
            'view_orders',
            'update_order_status'
        ]);

        $deliveryManager = Role::create(['name' => 'Delivery Manager', 'guard_name' => 'web']);
        $deliveryManager->givePermissionTo([
            'view_orders',
            'update_order_status',
            'manage_orders'
        ]);

        $customer = Role::create(['name' => 'Customer', 'guard_name' => 'web']);
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
    }
} 