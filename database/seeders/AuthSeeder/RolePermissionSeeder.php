<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

//php artisan db:seed --class=RolePermissionSeeder

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
        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        foreach ($studentPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'api']);

        // Assign permissions
        $adminRole->syncPermissions($adminPermissions);
        $studentRole->syncPermissions($studentPermissions);
    }
}
