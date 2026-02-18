<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grades')->insert([
            [
                'student_id' => 1,
                'score' => 95.50,
                'letter_grade' => 'A',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'score' => 82.00,
                'letter_grade' => 'B',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'score' => 68.75,
                'letter_grade' => 'C',
                'term' => 'Term 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 1,
                'score' => 88.20,
                'letter_grade' => 'B+',
                'term' => 'Term 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
