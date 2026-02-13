<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('classrooms')->insert([
            [
                'name' => 'Class A',
                'location' => 'Building 1 - Room 101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Class B',
                'location' => 'Building 1 - Room 102',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Class C',
                'location' => 'Building 2 - Room 201',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Computer Lab',
                'location' => 'Building 3 - Lab 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Science Room',
                'location' => 'Building 2 - Room 202',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
