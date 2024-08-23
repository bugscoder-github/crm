<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quotation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
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

        'company' => null,
        'premise_type' => null,
        'customer_name' => null,
        'phone' => null,
        'email' => null,
        'delivery_address' => null,
        'billing_address' => null,
        'is_same_billing_address' => null,

        'currency' => null,
        'currency_symbol' => null,
        'sub_total' => 0,
        'total_discount' => 0,
        'total_tax' => 0,
        'total_amount' => 0
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id',
        'quotation_type',
        'frequency_type',
        'frequency',
        'quotation_number',
        'quotation_date',
        'discount_code',
        
        'company',
        'premise_type',
        'customer_name',
        'phone',
        'email',
        'delivery_address',
        'billing_address',
        'is_same_billing_address',

        'currency',
        'currency_symbol',
        'sub_total',
        'total_discount',
        'total_tax',
        'total_amount'
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
     * Interact with the total discount
     */
    protected function totalDiscount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the total tax
     */
    protected function totalTax(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

    /**
     * Interact with the total amount
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value / 100,
            set: fn (string $value) => $value * 100,
        );
    }

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
     * Relationship has many quotation taxes
     */
    public function taxes() :HasMany
    {
        return $this->hasMany(QuotationTax::class);
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

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('quotation')->logAll();
    }
}
