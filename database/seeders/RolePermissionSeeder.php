<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin permissions
        $adminPermissions = [
            'manage system',
            'manage users',
            'manage attendance',
            'view attendance reports',
            'manage fees',
            'view payments',
            'generate reports',
            'export reports',
        ];
        // Student permissions
        $studentPermissions = [
            'view profile',
            'edit profile',
            'view attendance',
            'view payments',
            'download reports',
        ];
        // Create permissions
        foreach (array_merge($adminPermissions, $studentPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Assign permissions
        $adminRole->syncPermissions($adminPermissions);
        $studentRole->syncPermissions($studentPermissions);
    }
}
