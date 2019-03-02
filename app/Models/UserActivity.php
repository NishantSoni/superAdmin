<?php

namespace App\Models;

use App\Traits\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
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
        'entity_type', 'entity_id', 'column_name', 'old_value', 'modified_value', 'modified_by',
    ];

    /**
     * Polymorphic relation to user table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
