<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillble = [
        'student_code',
        'user_id',
        'student_code',
        'gender',
        'phone_number',
        'dob',
        'address',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
}
