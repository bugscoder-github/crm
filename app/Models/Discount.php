<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_redemption' => 'boolean',
        'valid_from' => 'datetime:Y-m-d',
        'valid_to' => 'datetime:Y-m-d'
    ];

    /**
     * Interact with the amount
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

     /**
     * Interact with the minimum amount
     */
    protected function minimumAmount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }


    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship has many discount codes
     */
    public function discountCodes() :HasMany
    {
        return $this->hasMany(DiscountCode::class);
    }

    /**
     * Relationship has many discount items
     */
    public function discountItems() :HasMany
    {
        return $this->hasMany(DiscountItem::class);
    }

    /**
     * Get all of the customers that are assigned this discount.
     */
    public function customers(): MorphToMany
    {
        return $this->morphedByMany(Customer::class, 'discount_item');
    }
    
    /**
     * Get all of the services that are assigned this discount.
     */
    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'discount_item');
    }

    /**
     * Get all of the service category that are assigned this discount.
     */
    public function serviceCategories(): MorphToMany
    {
        return $this->morphedByMany(ServiceCategory::class, 'discount_item');
    }
}
