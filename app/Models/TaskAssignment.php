<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAssignment extends Model
{
    protected $fillable = ['task_id', 'planting_season_id', 'employee_id', 'assigned_date', 'completion_date'];

    public function agriculturalTask(): BelongsTo
    {
        return $this->belongsTo(AgriculturalTask::class, 'task_id');
    }

    public function plantingSeason(): BelongsTo
    {
        return $this->belongsTo(PlantingSeason::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
