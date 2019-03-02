<?php

namespace App\Repositories;

use App\Models\UserActivity;

class UserActivityRepository extends Repository
{
    /**
     * To initialize class ovjects/variables.
     *
     * @param UserActivity $model
     */
    public function __construct(UserActivity $model)
    {
        $this->model = $model;
    }
}
