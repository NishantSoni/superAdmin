<?php
namespace App\Extensions;

use PragmaRX\Google2FALaravel\Exceptions\InvalidOneTimePassword;
/**
 * Class InvalidOneTimePasswordException
 * Extend the "PragmaRX\Google2FALaravel\Exceptions\InvalidOneTimePassword;" class to handle the exceptions.
 *
 * @package App\Extensions
 */
class InvalidOneTimePasswordException extends InvalidOneTimePassword
{
    /**
     * Render method will handle the exception accordingly.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function render()
    {
        return redirect('/users')->with(config('frontendMessages.ERROR'),
            config('frontendMessages.EXCEPTION_MESSAGES.INVALID_SECRET_CODE'));
    }
}
