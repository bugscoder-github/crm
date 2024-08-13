<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quotation_date' => 'datetime:Y-m-d',
        'is_shipping_address' => 'boolean'
    ];

    /**
     * The attributes
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'lead_id' => null,
        'quotation_type' => null,
        'frequency_type' => null,
        'frequency' => 1,
        'quotation_number' => null,
        'quotation_date' => null,
        'discount_code' => null,
        'is_shipping_address' => false,
        'currency' => null,
        'currency_symbol' => null,
        'tax_name' => null,
        'tax_type' => null,
        'tax_charge_type' => null,
        'tax_rate' => 0,
        'sub_total' => 0,
        'total_discount' => 0,
        'total_tax' => 0,
        'total_amount' => 0
    ];

    /**
     * Relationship has many quotation invoices
     */
    public function invoices() :HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Relationship has many quotation items
     */
    public function items() :HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Relationship has many quotation addresses
     */
    public function addresses() :HasMany
    {
        return $this->hasMany(QuotationAddress::class);
    }

    /**
     * Relationship has many quotation discounts
     */
    public function discounts() :HasMany
    {
        return $this->hasMany(QuotationDiscount::class);
    }

    /**
     * Relationship has many quotation discounts based on type
     */
    public function itemDiscounts() :HasMany
    {
        return $this->hasMany(QuotationDiscount::class)->where('quotation_discount_type', 'quotation');
    }
}
