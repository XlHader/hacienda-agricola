<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgriculturalTask extends Model
{
    protected $fillable = ['name', 'description'];

    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class, 'task_id');
    }
}
