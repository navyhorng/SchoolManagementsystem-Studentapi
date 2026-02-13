<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomTeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure classroom_id/teacher_id exist (run TeacherSeeder + ClassroomSeeder first)

        DB::table('classroom_teachers')->insert([
            // Class A has 2 teachers
            [
                'classroom_id' => 1,
                'teacher_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classroom_id' => 1,
                'teacher_id' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Class B has 1 teacher
            [
                'classroom_id' => 2,
                'teacher_id' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Class C has 2 teachers (one inactive example)
            [
                'classroom_id' => 3,
                'teacher_id' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classroom_id' => 3,
                'teacher_id' => 5,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Computer Lab has 1 teacher
            [
                'classroom_id' => 4,
                'teacher_id' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
