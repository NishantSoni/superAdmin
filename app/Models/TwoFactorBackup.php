<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class TwoFactorBackup extends Model
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
        'user_id', 'backup_code',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'two_factor_backups';
}
