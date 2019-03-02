<?php

namespace App\Repositories;

use App\Models\TwoFactorAuth;

class TwoFactorAuthRepository extends Repository
{
    /**
     * To initialize class ovjects/variables.
     *
     * @param TwoFactorAuth $model
     */
    public function __construct(TwoFactorAuth $model)
    {
        $this->model = $model;
    }
}
