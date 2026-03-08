<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
        'gender',
        'email',
        'phone_number',
    ];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_teachers'
        )->withPivot('is_active')->withTimestamps();
    }

}
