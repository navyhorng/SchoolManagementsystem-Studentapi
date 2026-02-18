<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reports')->insert([
            [
                'student_id' => 1,
                'attendance_summary' => 'Present: 18 days, Absent: 2 days, Late: 1 day',
                'grade_summary' => 'Average Score: 90 (A)',
                'fee_summary' => 'All fees paid for the term',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'attendance_summary' => 'Present: 15 days, Absent: 5 days, Late: 0 days',
                'grade_summary' => 'Average Score: 78 (B)',
                'fee_summary' => 'Outstanding balance: $50',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'attendance_summary' => 'Present: 12 days, Absent: 7 days, Late: 3 days',
                'grade_summary' => 'Average Score: 65 (C)',
                'fee_summary' => 'Partial payment received',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
