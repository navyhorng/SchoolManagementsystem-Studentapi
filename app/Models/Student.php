<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
{
    use CrudTrait;

    protected $fillable = [
        'student_code',
        'user_id',
        'gender',
        'phone_number',
        'dob',
        'address',
        'classroom_id',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
