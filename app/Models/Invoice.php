<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model {
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'datetime:Y-m-d',
        'is_shipping_address' => 'boolean'
    ];

    /**
     * The attributes
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'quotation_id' => null,
        'invoice_number' => null,
        'invoice_date' => null,
        'discount_code' => null,

        'company' => null,
        'premise_type' => null,
        'customer_name' => null,
        'phone' => null,
        'email' => null,
        'delivery_address' => null,
        'billing_address' => null,
        'is_same_billing_address' => false,

        'currency' => null,
        'currency_symbol' => null,
        'sub_total' => 0,
        'total_discount' => 0,
        'total_tax' => 0,
        'total_amount' => 0,
        'status' => 'unpaid'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quotation_id',
        'invoice_number',
        'invoice_date',
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
        'total_amount',
        'status'
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
     * Relationship has many invoice items
     */
    public function items() :HasMany
    {
        return $this->hasMany(invoiceItem::class);
    }

    /**
     * Relationship has many invoice taxes
     */
    public function taxes() :HasMany
    {
        return $this->hasMany(invoiceTax::class);
    }

    /**
     * Relationship has many invoice discounts
     */
    public function discounts() :HasMany
    {
        return $this->hasMany(invoiceDiscount::class);
    }

    /**
     * Relationship has many invoice discounts based on type
     */
    public function itemDiscounts() :HasMany
    {
        return $this->hasMany(invoiceDiscount::class)->where('invoice_discount_type', 'invoice');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('invoice')->logAll();
    }
}
