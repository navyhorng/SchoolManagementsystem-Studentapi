<?php

// database/seeders/FeePaymentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FeePayment;
use Carbon\Carbon;

class FeePaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Get users that are students
        $students = User::whereHas('student')->get();

        foreach ($students as $student) {

            // Create multiple fee payments per student
            for ($i = 1; $i <= 4; $i++) {

                $dueDate = Carbon::now()->subMonths(rand(1,6));

                $status = collect(['Paid','Unpaid','Partial'])->random();

                FeePayment::create([
                    'student_id' => $student->id,
                    'amount' => rand(50, 300),
                    'status' => $status,
                    'due_date' => $dueDate,
                    'payment_date' => $status === 'Paid'
                        ? $dueDate->copy()->addDays(rand(1,10))
                        : null,
                ]);
            }
        }
    }
}
