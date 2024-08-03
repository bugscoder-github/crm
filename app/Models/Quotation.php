<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quotation extends Model {
    use SoftDeletes, HasFactory, LogsActivity;

    protected $primaryKey = "quotation_id";
    protected $guarded = [];

    public function items(): HasMany {
        return $this->hasMany(QuotationItems::class, "quotation_id");
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('quotation')->logAll();
    }
}
