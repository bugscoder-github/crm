<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sequence_type',
        'prefix',
        'number',
        'suffix',
    ];

    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
