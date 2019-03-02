<?php

namespace App\Models;

use App\Traits\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TwoFactorAuth extends Model
{
    use Uuids;

    /**
     * To disable auto-increment
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'google2fa_enable', 'google2fa_secret'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'two_factor_auths';

    /**
     * Eloquent relation to users table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
