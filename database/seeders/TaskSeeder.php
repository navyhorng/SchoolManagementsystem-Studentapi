<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $defaultStudentEmails = [
            'lyhoung@student.local',
            'kungnita@student.local',
            'sreypichkeo@student.local',
            'zavyvy92@gmail.com',
        ];

        $students = User::whereIn('email', $defaultStudentEmails)->get()->values();

        if ($students->isEmpty()) {
            return;
        }

        $tasks = [
            [
                'title' => 'Math worksheet - Algebra basics',
                'description' => 'Complete exercises 1 to 10 from chapter 3.',
                'category' => 'Homework',
                'due_date' => now()->addDays(2)->toDateString(),
                'priority' => 'Medium',
                'is_completed' => false,
            ],
            [
                'title' => 'Science quiz preparation',
                'description' => 'Review notes on cells and ecosystems for Friday quiz.',
                'category' => 'Exam',
                'due_date' => now()->addDays(4)->toDateString(),
                'priority' => 'High',
                'is_completed' => false,
            ],
            [
                'title' => 'Read English short story',
                'description' => 'Read pages 20-35 and write a short summary.',
                'category' => 'Homework',
                'due_date' => now()->addDays(3)->toDateString(),
                'priority' => 'Low',
                'is_completed' => false,
            ],
            [
                'title' => 'Organize study schedule',
                'description' => 'Plan study sessions for all subjects this week.',
                'category' => 'Personal',
                'due_date' => now()->addDays(1)->toDateString(),
                'priority' => 'Medium',
                'is_completed' => true,
            ],
            [
                'title' => 'History chapter revision',
                'description' => 'Revise chapter 5 and list key historical events.',
                'category' => 'Exam',
                'due_date' => now()->addDays(5)->toDateString(),
                'priority' => 'High',
                'is_completed' => false,
            ],
        ];

        foreach ($tasks as $index => $taskData) {
            $student = $students[$index % $students->count()];

            Task::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'title' => $taskData['title'],
                ],
                [
                    'description' => $taskData['description'],
                    'category' => $taskData['category'],
                    'due_date' => $taskData['due_date'],
                    'priority' => $taskData['priority'],
                    'is_completed' => $taskData['is_completed'],
                    'completed_at' => $taskData['is_completed'] ? now() : null,
                ]
            );
        }
    }
}
