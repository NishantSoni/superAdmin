<?php

namespace App\Repositories;

use App\Models\TwoFactorBackup;

class TwoFactorBackupRepository extends Repository
{
    /**
     * To initialize class ovjects/variables.
     *
     * @param TwoFactorBackup $model
     */
    public function __construct(TwoFactorBackup $model)
    {
        $this->model = $model;
    }
}
