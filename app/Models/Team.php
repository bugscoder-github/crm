<?php

namespace App\Models;

use Laratrust\Models\Team as LaratrustTeam;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends LaratrustTeam
{
    public $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Relationship has many customers
     */
    public function customers() :HasMany
    {
        return $this->hasMany(Customer::class, 'customer_id');
    }


    /**
     * Relationship has many taxes
     */
    public function taxes() :HasMany
    {
        return $this->hasMany(Tax::class);
    }

    /**
     * Relationship has many sequences
     */
    public function sequences() :HasMany
    {
        return $this->hasMany(Sequence::class);
    }

    /**
     * Relationship has many discounts
     */
    public function discounts() :HasMany
    {
        return $this->hasMany(Discount::class);
    }

    /**
     * Relationship has many currencies
     */
    public function currencies() :HasMany
    {
        return $this->hasMany(Currency::class);
    }

    /**
     * Relationship has many services
     */
    public function services() :HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Relationship has many template services
     */
    public function templateServices() :HasMany
    {
        return $this->hasMany(TemplateService::class);
    }

    /**
     * Get Team Currencies
     */
    public function getTeamPrimary()
    {
        return $this->currencies()->where('is_primary', true)->firstOrFail();
    }

    /**
     * Get Team Taxes Data Based on Currency
     */
    public function getCurrencyTaxes($currency)
    {
        return $this->taxes()->where('currency', $currency)->get();
    }

    /**
     * Relationship has many quotations
     */
    public function quotations() :HasMany
    {
        return $this->hasMany(Quotation::class);
    }

     /**
     * Relationship has many invoices
     */
    public function invoices() :HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Finding Sequence Type
     */
    public function findSequenceType($type)
    {
        return $this->sequences()->where('sequence_type', $type)->firstOrFail();
    }
}
