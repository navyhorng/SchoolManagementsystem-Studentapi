<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 dummy students if not exist
        $students = User::factory()->count(3)->create([
            'password' => Hash::make('password'),
        ]);

        $categories = ['Homework', 'Exam', 'Personal'];
        $priorities = ['Low', 'Medium', 'High'];

        foreach ($students as $student) {
            for ($i = 1; $i <= 5; $i++) {

                $isCompleted = fake()->boolean();

                Task::create([
                    'user_id' => $student->id,
                    'title' => fake()->sentence(3),
                    'description' => fake()->sentence(10),
                    'category' => $categories[array_rand($categories)],
                    'due_date' => fake()->dateTimeBetween('now', '+30 days'),
                    'priority' => $priorities[array_rand($priorities)],
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now() : null,
                ]);
            }
        }
    }
}
