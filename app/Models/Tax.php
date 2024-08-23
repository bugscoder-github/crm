<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    /**
     * The attributes
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'name' => null,
        'currency' => null,
        'tax_type' => null,
        'charge_type' => null,
        'amount' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'currency',
        'tax_type',
        'charge_type',
        'amount',
    ];

    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
