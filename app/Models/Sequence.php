<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory;

    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
