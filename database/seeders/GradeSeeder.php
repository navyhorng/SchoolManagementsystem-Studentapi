<?php

// database/seeders/GradeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        // Get users who are students
        $students = User::whereHas('student')->get();

        $terms = [
            'Term 1',
            'Term 2',
            'Final Term'
        ];

        foreach ($students as $student) {

            foreach ($terms as $term) {

                $score = rand(50, 100);

                Grade::create([
                    'student_id' => $student->id,
                    'score' => $score,
                    'letter_grade' => $this->getLetterGrade($score),
                    'term' => $term,
                ]);
            }
        }
    }

    private function getLetterGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';

        return 'F';
    }
}
