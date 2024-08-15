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

    // protected $appends = ['lead_status'];
    // public function getLeadStatusAttribute() {
    // 	$status = 1; // new
    //  	if ($this->read_at != null) { $status = 2; } //pending
    //   	if ($this->leadComment_count > 0) { $status = 3; } //wip
    //    	if ($this->done_at != null) { $status = 4; } //done
    //     return $status;
    // }

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function scopeJoinUser($query) {
        $join = '';
        // $join = $query->leftJoin("users", "users.id", "=", "leads.user_id");
        // dd(me()->current_team_id);

        if (isAdmin()) {
            // $join = $join->where('users.current_team_id', me()->current_team_id);
        }

        return $join;
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->useLogName('lead')->logAll();
    }
}
