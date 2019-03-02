<?php

namespace App;

use App\Models\AuthenticationLog;
use App\Models\TwoFactorAuth;
use App\Models\TwoFactorBackup;
use App\Models\UserActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
        'username', 'first_name', 'last_name',
        'address', 'house_number', 'postal_code',
        'city', 'telephone_number', 'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * To disable auto-increment
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Eloquent relation with "two_factor_auths" table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuth::class);
    }

    /**
     * Eloquent relation with "two_factor_backups"
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function twoFactorAuthBackups()
    {
        return $this->hasMany(TwoFactorBackup::class);
    }

    /**
     * Eloquent relation to get the user history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function userHistory()
    {
        return $this->MorphMany(UserActivity::class, 'entity')->with('modifiedBy')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Relation to get last login information of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLastLoginDetails()
    {
        return $this->hasMany(AuthenticationLog::class)->orderBy('created_at', 'desc')->limit(1);
    }
}
