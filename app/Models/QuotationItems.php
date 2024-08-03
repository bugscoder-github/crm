<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItems extends Model {
    use SoftDeletes, HasFactory;

    protected $primaryKey = "quotationItem_id";
    // const CREATED_AT = "lead_createdAt";
    // const UPDATED_AT = "lead_updatedAt";

    protected $guarded = [];

    // public function quotation(): BelongsTo {
    //     return $this->belongsTo(Quotation::class, "quotation_id");
    // }
}
