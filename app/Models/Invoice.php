<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model {
    use SoftDeletes, HasFactory, LogsActivity;

    protected $primaryKey = "invoice_id";
    protected $guarded = [];

    public function items(): HasMany {
        return $this->hasMany(InvoiceItems::class, "invoice_id");
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('invoice')->logAll();
    }

}
