<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasOne};

class Employee extends Model
{
    protected $fillable = ['first_name', 'last_name', 'identification', 'hire_date', 'role'];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'employee_skills')
            ->withPivot('certification_date')
            ->withTimestamps();
    }

    public function trainings(): BelongsToMany
    {
        return $this->belongsToMany(Training::class, 'training_participations')
            ->withPivot('completion_status')
            ->withTimestamps();
    }

    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
