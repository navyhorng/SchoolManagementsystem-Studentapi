<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultStdSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Ensure role exists
        $studentRole = Role::where('name', 'student')
            ->where('guard_name', 'api')
            ->firstOrFail();

        $students = [
            ['name' => 'Student One', 'email' => 'student1@example.com'],
            ['name' => 'Student Two', 'email' => 'student2@example.com'],
            ['name' => 'Student Three', 'email' => 'student3@example.com'],
        ];

        foreach ($students as $data) {
            $student = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('student123'),
                ]
            );

            // ✅ Prevent duplicate role assignment
            if (! $student->hasRole($studentRole)) {
                $student->assignRole($studentRole);
            }
        }
    }
}
