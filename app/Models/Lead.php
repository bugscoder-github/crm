<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lead extends Model {
    use SoftDeletes, HasFactory, LogsActivity;

    protected $primaryKey = "lead_id";
    const CREATED_AT = "lead_createdAt";
    const UPDATED_AT = "lead_updatedAt";

    protected $appends = ['lead_status'];
    public function getLeadStatusAttribute() {
    	$status = 0; // new
     	if ($this->read_at != null) { $status = 1; } //pending
      	if ($this->leadComment_count > 0) { $status = 2; } //wip
       	if ($this->done_at != null) { $status = 3; } //done
        return $status;
    }

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('lead')->logAll();
    }
}
