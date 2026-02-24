<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
<<<<<<< HEAD
     *
=======
>>>>>>> main
     * php artisan db:seed --class=DatabaseSeeder
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DefaultAdminSeeder::class,
            DefaultStdSeeder::class,
            TeacherSeeder::class,
            ClassroomSeeder::class,
            ClassroomTeacherSeeder::class,
            TaskSeeder::class,
            AttendanceSeeder::class,
            GradeSeeder::class,
            FeePaymentSeeder::class,
        ]);

    }
}
