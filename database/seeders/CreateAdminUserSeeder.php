<?php
/**
 * @Author: Anwarul
 * @Date: 2025-12-31 11:31:40
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 17:12:23
 * @Description: Innova IT
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Admin user create/update
        $user = Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456')
            ]
        );

        // Role create/update
        $role = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'admin'
        ]);

        // Fetch all admin permissions
        $permissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();

        // Assign permissions to role
        $role->syncPermissions($permissions);

        // Assign role to admin user
        $user->assignRole($role);
    }
}
