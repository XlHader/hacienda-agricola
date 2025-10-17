<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityStandard extends Model
{
    protected $fillable = ['product_id', 'grade', 'criteria'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
