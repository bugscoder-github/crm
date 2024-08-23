<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class QuotationTax extends Model
{
    use HasFactory;

    /**
     * The attributes
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'tax_name' => null,
        'tax_type' => null,
        'tax_charge_type' => null,
        'tax_rate' => 0,
        'tax_amount' => 0
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tax_name',
        'tax_type',
        'tax_charge_type',
        'tax_rate',
        'tax_amount'
    ];

    /**
     * Interact with the tax rate
     */
    protected function taxRate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the tax amount
     */
    protected function taxAmount(): Attribute
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
}
