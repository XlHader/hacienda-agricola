<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class InputBatch extends Model
{
    protected $fillable = ['input_id', 'batch_number', 'quantity', 'expiration_date', 'cost'];

    public function agriculturalInput(): BelongsTo
    {
        return $this->belongsTo(AgriculturalInput::class, 'input_id');
    }

    public function fertilizerApplications(): HasMany
    {
        return $this->hasMany(FertilizerApplication::class);
    }

    public function phytosanitaryTreatments(): HasMany
    {
        return $this->hasMany(PhytosanitaryTreatment::class);
    }
}
