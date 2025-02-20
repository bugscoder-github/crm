<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Lead;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions, SoftDeletes, HasFactory, Notifiable;

    protected $appends = ['roles', 'teams'];
    public function getRolesAttribute() {
        // return $this->roles()->where('team_id', $this->currentTeam()->id)->first();
        return $this->roles()->first();
        // return $this->rolesTeams();
    }
    public function getTeamsAttribute() {
        // return $this->roles->pluck('name');
        return $this->currentTeam();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAllPermissionsAttribute()
    {
        return $this->allPermissions();
    }

    public function currentTeam()
    {
        return $this->rolesTeams()->where('id', $this->current_team_id)->first();
    }

    // public function isAdmin()
    // {
    //     return $this->hasRole('Owner') || $this->hasRole('Admin');
    // }
    public function lead(): HasMany {
        return $this->hasMany(Lead::class);
    }

    public function getNotificationCount() {
        $userList = [$this->id];
        if (isOwner()) { $userList[] = '0'; }
        return Lead::whereIn('user_id', $userList)->whereAnd('read_at', null)->count();
    }
}
