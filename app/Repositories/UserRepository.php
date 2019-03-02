<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Auth;
use Okipa\LaravelBootstrapTableList\TableList;

class UserRepository extends Repository
{
    /**
     * To initialize class objects/variables.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Method to get model class
     *
     * @return string
     */
    public function getModelClass() : string
    {
        return User::class;
    }

    /**
     * Method to create the users list as view by using external "Okipa\LaravelBootstrapTableList" package.
     *
     * @return TableList
     */
    public function getUsersList() : TableList
    {
        return app(TableList::class)
            ->setModel(User::class)
            ->setRoutes([
                'index'      => ['alias' => 'users.index', 'parameters' => []],
                'edit'       => ['alias' => 'users.edit', 'parameters' => []],
                'destroy'    => ['alias' => 'users.destroy', 'parameters' => []],
            ])
            ->setRowsNumber(10)
            ->addQueryInstructions(function ($query){
                $query->select('users.*');
                $query->selectRaw('MAX(authentication_logs.created_at) as last_login_at');
                $query->leftJoin('authentication_logs', 'authentication_logs.user_id', '=', 'users.id');
                $query->where('users.id', '!=', Auth::id());
                $query->where('users.is_active', 1);
                $query->groupBy('users.id');
            });
    }
}
