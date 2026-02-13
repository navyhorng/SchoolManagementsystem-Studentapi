<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'location',
    ];

    public function attendances()
    {
        return $this->hasMany(Classroom::class);
    }

    public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'classroom_teachers')
        ->withPivot('is_active')
        ->withTimestamps();
}

}
