<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'score',
        'letter_grade',
        'term',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A grade belongs to one student (user)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
