<?php
namespace App\Extensions;

use App\Models\TwoFactorBackup;
use PragmaRX\Google2FALaravel\Events\EmptyOneTimePasswordReceived;
use PragmaRX\Google2FALaravel\Events\LoginFailed;
use PragmaRX\Google2FALaravel\Events\LoginSucceeded;
use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator;

/**
 * Class Google2FAAuthenticator
 * Extend the "PragmaRX\Google2FALaravel\Support\Authenticator" class to verify 2FA according to our app needs.
 *
 * @package App\Extensions
 */
class Google2FAAuthenticator extends Authenticator
{
    /**
     * Method to override existing method to verify the 2FA.
     *
     * @return bool
     */
    protected function canPassWithoutCheckingOTP() : bool
    {
        if (empty($this->getUser()->twoFactorAuth) || $this->getUser()->twoFactorAuth == null) {
            return true;
        }

        if ($this->verifyTwoFactorAuthentication()) {
            return true;
        }

        // Here we are checking the backup code.
        if ($this->checkBackupCode()) {
            $this->login();

            return true;
        }

        return false;
    }

    /**
     * Overriding the existing method for getting the google secret key for the particular user.
     *
     * @return mixed
     * @throws InvalidSecretKey
     */
    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->twoFactorAuth->{$this->config('otp_secret_column')};
        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }

    /**
     * Fire login event.
     *
     * @param $succeeded
     * @return mixed
     */
    protected function fireLoginEvent($succeeded)
    {
        event(
            $succeeded
                ? new LoginSucceeded($this->getUser())
                : new LoginFailed($this->getUser())
        );

        return $succeeded;
    }

    /**
     * Overriding the existing method for checking OTP according to our application needs.
     *
     * @return bool
     * @throws InvalidOneTimePasswordException
     */
    protected function checkOTP()
    {
        if (!$this->inputHasOneTimePassword()) {
            return false;
        }

        if ($isValid = $this->verifyOneTimePassword()) {
            $this->login();
        }

        if (!$isValid) {
            throw new InvalidOneTimePasswordException();
        }

        return $this->fireLoginEvent($isValid);
    }

    /**
     * Method to get one time password, if it is empty then it will thrown an error.
     *
     * @return mixed
     * @throws InvalidOneTimePasswordException
     */
    protected function getOneTimePassword()
    {
        if (is_null($password = $this->getInputOneTimePassword()) || empty($password)) {
            event(new EmptyOneTimePasswordReceived());

            if ($this->config('throw_exceptions', true)) {
                throw new InvalidOneTimePasswordException();
            }
        }

        return $password;
    }

    /**
     * Method to verify the 2FA code entered by the user.
     *
     * @return bool
     */
    protected function verifyTwoFactorAuthentication()
    {
        return
            !$this->getUser()->twoFactorAuth->google2fa_enable ||
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }

    /**
     * Method to check the back up code for the user.
     * This will be required, if user lost mobile phone.
     *
     * @return bool
     */
    private function checkBackupCode()
    {
        $user = $this->getUser();

        $userBackupCodes = $user->twoFactorAuthBackups ? $user->twoFactorAuthBackups->pluck('backup_code')->toArray() : null;

        if ($userBackupCodes == null || empty($userBackupCodes)) {
            return false;
        }

        if (in_array($this->request->input('one_time_password'), $userBackupCodes)) {
            TwoFactorBackup::where(['user_id' => $user->id, 'backup_code' => $this->request->input('one_time_password')])->delete();

            return true;
        }

        return false;
    }
}
