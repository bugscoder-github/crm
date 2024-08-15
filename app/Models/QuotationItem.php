<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * The attributes
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'item_type' => null,
        'service_id' => null,
        'name' => null,
        'description' => null,
        'quantity' => 1,
        'unit_amount' => 0,
        'discount_amount' => 0,
        'sub_total' => 0,
        'line_amount' => 0,
        'is_enabled' => true
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_type',
        'service_id',
        'name',
        'description',
        'quantity',
        'unit_amount',
        'discount_amount',
        'sub_total',
        'line_amount',
        'is_enabled'
    ];

    /**
     * Interact with the sub total
     */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the unit amount
     */
    protected function unitAmount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the discount amount
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the line amount
     */
    protected function lineAmount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Relationship belongs to quotation
     */
    public function quotation() :BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get all of the discounts for the service.
     */
    public function discounts() :HasMany
    {
        return $this
            ->quotation
            ->discounts()
            ->where('quotation_discount_type', 'quotation_item')
            ->where('quotation_discount_id', $this->id);
    }
}
