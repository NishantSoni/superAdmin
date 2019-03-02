<?php

namespace App\Exceptions;

use ErrorException;

/**
 * Class SuperAdminException
 * Global exception class.
 *
 * @package App\Exceptions
 */
class SuperAdminException extends ErrorException
{
    /**
     * Method to handle the exception and return with the particular message.
     *
     * @param $request
     * @param SuperAdminException $exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function render($request, SuperAdminException $exception)
    {
        return redirect('/users')->with(config('frontendMessages.ERROR'), $exception->getMessage());
    }
}
