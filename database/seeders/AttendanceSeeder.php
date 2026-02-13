<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendances')->insert([
            [
                'student_id' => 1,
                'classroom_id' => 1,
                'date' => '2026-02-10',
                'status' => 'Present',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'classroom_id' => 1,
                'date' => '2026-02-10',
                'status' => 'Late',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'classroom_id' => 2,
                'date' => '2026-02-10',
                'status' => 'Absent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 1,
                'classroom_id' => 1,
                'date' => '2026-02-11',
                'status' => 'Present',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'classroom_id' => 1,
                'date' => '2026-02-11',
                'status' => 'Present',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
