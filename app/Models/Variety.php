<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Variety extends Model
{
    protected $fillable = ['crop_id', 'name', 'characteristics'];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function plantingSeasons(): HasMany
    {
        return $this->hasMany(PlantingSeason::class);
    }
}
