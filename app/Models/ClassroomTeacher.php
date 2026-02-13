<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomTeacher extends Model
{

    protected $fillable = [
        'classroom_id',
        'teacher_id',
        'is_active',
    ];
}
