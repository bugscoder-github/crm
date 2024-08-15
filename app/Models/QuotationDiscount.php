<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class QuotationDiscount extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'amount',
        'quotation_discount_type',
        'quotation_discount_id',
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
}
