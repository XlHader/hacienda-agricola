<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FertilizerApplication extends Model
{
    protected $fillable = ['fertilization_plan_id', 'input_batch_id', 'application_date', 'quantity'];

    public function fertilizationPlan(): BelongsTo
    {
        return $this->belongsTo(FertilizationPlan::class);
    }

    public function inputBatch(): BelongsTo
    {
        return $this->belongsTo(InputBatch::class);
    }
}
