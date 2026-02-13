<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teachers')->insert([
            [
                'name' => 'John Smith',
                'gender' => 'Male',
                'email' => 'john.smith@example.com',
                'phone_number' => '012345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anna Lee',
                'gender' => 'Female',
                'email' => 'anna.lee@example.com',
                'phone_number' => '098765432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Chen',
                'gender' => 'Male',
                'email' => 'david.chen@example.com',
                'phone_number' => '011223344',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sok Dara',
                'gender' => 'Male',
                'email' => 'sok.dara@example.com',
                'phone_number' => '010998877',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chantha Vann',
                'gender' => 'Female',
                'email' => 'chantha.vann@example.com',
                'phone_number' => '015667788',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
