<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Class UpdateRequestValidatorServiceProvider
 * Is used for checking the the unique fields.
 * Unique fields should not be matched with others, EXCEPT SELF.
 *
 * @package App\Providers
 */
class UpdateRequestValidatorServiceProvider extends ServiceProvider
{
    /**
     * @var UserRepository $userRepository
     */
    private  $userRepository;

    /**
     * Bootstrap update request validation service provider.
     *
     * @param UserRepository $userRepository
     */
    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        Validator::extend('CheckFieldForUpdate', function ($attribute, $value, $parameters, $validator) {
            return $this->checkFieldForUpdate($attribute, $value, $parameters);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Method to check field value in the database.
     * If user is being updated, then unique values should not conflict with other users values, EXCEPT SELF.
     *
     * @param string $attribute
     * @param string $value
     * @param array $userForUpdate
     * @return bool
     */
    public function checkFieldForUpdate(string $attribute, string $value, array $userForUpdate) : bool
    {
        $condition = [
            [$attribute, '=', $value],
            ['id', '!=', current($userForUpdate)]
        ];

        $result = $this->userRepository->isFieldValueExists($condition);

        return $result ? false : true;
    }
}