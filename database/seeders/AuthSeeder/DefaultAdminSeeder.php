<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=DefaultAdminSeeder
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        // Assign admin role (web guard)
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
