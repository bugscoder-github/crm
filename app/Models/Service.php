<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'name' => null,
        'description' => null,
        'price' => 0
    ];
    
    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all of the discounts for the service.
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discount_item');
    }
}
