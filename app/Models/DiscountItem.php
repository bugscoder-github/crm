<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DiscountItem extends Model
{
    use HasFactory;

    /**
     * Relationship belongs to discount
     */
    public function discount() :BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}
