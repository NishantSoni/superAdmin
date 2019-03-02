<?php

namespace App\Repositories;

use App\Models\AuthenticationLog;

class AuthenticationLogRepository extends Repository
{
    /**
     * To initialize class ovjects/variables.
     *
     * @param AuthenticationLog $model
     */
    public function __construct(AuthenticationLog $model)
    {
        $this->model = $model;
    }
}
