<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeePaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('feePayments')->insert([
            [
                'student_id' => 1,
                'amount' => 100.00,
                'status' => 'Paid',
                'due_date' => Carbon::now()->subDays(10),
                'payment_date' => Carbon::now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'amount' => 120.00,
                'status' => 'Unpaid',
                'due_date' => Carbon::now()->addDays(5),
                'payment_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'amount' => 90.00,
                'status' => 'Partial',
                'due_date' => Carbon::now()->subDays(2),
                'payment_date' => Carbon::now()->subDay(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
