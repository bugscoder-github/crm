<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    use HasFactory;

    protected $primaryKey = "quotation_id";
    protected $guarded = [];

    public function items(): HasMany {
        return $this->hasMany(QuotationItems::class, "quotation_id");
    }
}
