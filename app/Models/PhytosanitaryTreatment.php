<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhytosanitaryTreatment extends Model
{
    protected $fillable = ['incident_id', 'input_batch_id', 'application_date', 'quantity'];

    public function phytosanitaryIncident(): BelongsTo
    {
        return $this->belongsTo(PhytosanitaryIncident::class, 'incident_id');
    }

    public function inputBatch(): BelongsTo
    {
        return $this->belongsTo(InputBatch::class);
    }
}
