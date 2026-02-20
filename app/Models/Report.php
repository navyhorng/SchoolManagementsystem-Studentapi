<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'attendance_summary',
        'grade_summary',
        'fee_summary',
        'term',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A report belongs to one student (user)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
