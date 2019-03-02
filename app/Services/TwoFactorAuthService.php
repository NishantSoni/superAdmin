<?php

namespace App\Services;

use App\Repositories\TwoFactorAuthRepository;
use App\Repositories\TwoFactorBackupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class TwoFactorAuthService
{
    /**
     * @var TwoFactorAuthRepository $authenticationLog
     */
    private $twoFactorAuthRepository;

    /**
     * @var Google2FA $google2Factor
     */
    private $googleTwoFactor;

    /**
     * @var TwoFactorBackupRepository $twoFactorBackupRepository
     */
    private $twoFactorBackupRepository;

    /**
     * Class level constants.
     *
     * To avoid the SONAR LINT errors.
     */
    const MIN_VALUE = 1000000000;
    const MAX_VALUE = 9999999999;
    const ENABLE = 1;
    const DISABLE = 0;


    /**
     * AuthenticationService constructor.
     * Initialize object/instances of the classes.
     *
     * @param TwoFactorAuthRepository $twoFactorAuthRepository
     * @param Google2FA $google2Factor
     * @param TwoFactorBackupRepository $twoFactorBackupRepository
     */
    public function __construct(TwoFactorAuthRepository $twoFactorAuthRepository, Google2FA $google2Factor,
                                TwoFactorBackupRepository $twoFactorBackupRepository)
    {
        $this->twoFactorAuthRepository = $twoFactorAuthRepository;
        $this->googleTwoFactor = $google2Factor;
        $this->twoFactorBackupRepository = $twoFactorBackupRepository;

        // It’s not secure to send secret keys to Google APIs, we have to explicitly allow it by this:
        $this->googleTwoFactor->setAllowInsecureCallToGoogleApis(true);
    }

    /**
     * Method to generate QR code for the two factor authentication.
     *
     * @return array
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function getTwoFactorAuthData()
    {
        $user = Auth::user();
        $userTwoFactorInfo = $user->twoFactorAuth;

        $google2fa_url = "";
        if ($userTwoFactorInfo != null) {
            $google2fa_url = $this->googleTwoFactor->getQRCodeGoogleUrl(
                config('constants.TWO_FA_COMPANY_NAME'),
                $user->email,
                $userTwoFactorInfo->google2fa_secret
            );
        }

        return [
            'user' => $user,
            'google2fa_url' => $google2fa_url,
            'userTwoFactorInfo' => $userTwoFactorInfo,
            'userTwoFactorBackupInfo' => $this->getBackupCodes(true),
        ];
    }

    /**
     * Method to generate secret code for the particular user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function generateTwoFactorSecretCode()
    {
        $twoFactorAuthData = [
            'user_id' => Auth::id(),
            'google2fa_secret' => $this->googleTwoFactor->generateSecretKey(),
        ];

        return $this->twoFactorAuthRepository->create($twoFactorAuthData);
    }

    /**
     * Method to enable 2FA for the user.
     *
     * @param Request $request
     * @return bool|int
     */
    public function enableTwoFactorAuthentication(Request $request)
    {
        $user = Auth::user();
        $userTwoFactorInfo = $user->twoFactorAuth;
        $enteredSecretCode = $request->get('verify-code');

        $result = $this->googleTwoFactor->verifyKey($userTwoFactorInfo->google2fa_secret, $enteredSecretCode);

        if ($result) {
            $this->twoFactorAuthRepository->update($userTwoFactorInfo->id, ['google2fa_enable' => self::ENABLE]);
        }

        return $result;
    }

    /**
     * Method to disable the 2FA for the user.
     *
     * @param Request $request
     * @return bool
     */
    public function disableTwoFactorAuthentication(Request $request) : bool
    {
        $user = Auth::user();

        if (!(Hash::check($request->get('current-password'), $user->password))) {
            return false;
        }

        $userTwoFactorInfo = $user->twoFactorAuth;

        return $this->twoFactorAuthRepository->update($userTwoFactorInfo->id, ['google2fa_enable' => self::DISABLE]);
    }

    /**
     * Method to generate 2FA random codes and store them in database.
     *
     * @return bool
     * @throws \Exception
     */
    public function generate2FaBackupCodes() : bool
    {
        return $this->generateAndStoreRandomCodes();
    }

    /**
     * Method to generate random numbers and store them as backup codes.
     *
     * @return bool
     * @throws \Exception
     */
    public function generateAndStoreRandomCodes() : bool
    {
        $userCodes = [];

        // Avoid database query in loop, and store secret codes in one query.
        for ($i = 0; $i < 5; $i++) {
            $code = rand(self::MIN_VALUE, self::MAX_VALUE);
            $codes[] = $code;

            $codeInfoToInsert = [
                'id' => Uuid::generate(),
                'user_id' => Auth::id(),
                'backup_code' => $code,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];

            $userCodes[] = $codeInfoToInsert;
        }

        $this->twoFactorBackupRepository->deleteBy(['user_id' => Auth::id()]);
        $this->twoFactorBackupRepository->insertMultipleRows($userCodes);

        return true;
    }

    /**
     * Method to get the backup codes.
     *
     * @param bool $asString
     * @return array|mixed
     */
    public function getBackupCodes($asString = false)
    {
        $user = Auth::user();

        $backupCodes =  $user->twoFactorAuthBackups ? $user->twoFactorAuthBackups->pluck('backup_code')->toArray() : [];

        if ($asString) {
            return str_replace(',', "\n", implode(',', $backupCodes));
        }

        return $backupCodes;
    }
}
