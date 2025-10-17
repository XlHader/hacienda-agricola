<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    protected $fillable = ['name', 'type', 'description'];

    public function varieties(): HasMany
    {
        return $this->hasMany(Variety::class);
    }

    public function phenologicalStages(): HasMany
    {
        return $this->hasMany(PhenologicalStage::class);
    }
}
