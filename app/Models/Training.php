<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date'];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'training_participations')
            ->withPivot('completion_status')
            ->withTimestamps();
    }
}
