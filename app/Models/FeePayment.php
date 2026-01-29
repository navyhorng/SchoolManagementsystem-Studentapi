<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $table = 'feePayments';
    protected $fillable = [
        'student_id',
        'amount',
        'status',
        'due_date',
        'payment_date'
    ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


}
