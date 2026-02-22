<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultStdSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')
            ->where('guard_name', 'api')
            ->firstOrFail();

        $students = [
            ['name' => 'LyHoung', 'email' => 'lyhoung@student.local'],
            ['name' => 'Kean Nita', 'email' => 'keannita@student.local'],
            ['name' => 'Kong Srey Pich Keo', 'email' => 'kongsreypichkeo@student.local'],
        ];

        foreach ($students as $data) {
             $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('student123'),
                ]
            );

            // 2️⃣ Assign STUDENT role to USER
            if (! $user->hasRole('student')) {
                $user->assignRole($studentRole);
            }
            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'student_code' => 'STD-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'gender'=> 'female',
                    'phone_number' => null,
                    'dob' => null,
                    'address' => null,
                ]
            );
        }
    }
}
