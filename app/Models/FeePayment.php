<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use CrudTrait;

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
