<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use CrudTrait;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'date',
        'status'
    ];

    public function student(){

        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

}
