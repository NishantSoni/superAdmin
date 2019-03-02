<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisableTwoFactorAuthRequest;
use App\Http\Requests\EnableTwoFactorAuthRequest;
use App\Services\TwoFactorAuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use PragmaRX\Google2FA\Exceptions\InsecureCallException;

class TwoFactorAuthController extends Controller
{
    /**
     * @var TwoFactorAuthService $twoFactorAuthService
     */
    private $twoFactorAuthService;

    /**
     * TwoFactorAuthController constructor.
     *
     * @param TwoFactorAuthService $twoFactorAuthService
     */
    public function __construct(TwoFactorAuthService $twoFactorAuthService)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
    }

    /**
     * Method to generate QR code, and generate it for further processing.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show2faForm()
    {
        try {
            $twoFactorAuthData = $this->twoFactorAuthService->getTwoFactorAuthData();
        } catch (InsecureCallException $exception) {
            return view('admin.twoFactorAuth.enableTwoFactorAuth')
                ->with(config('frontendMessages.ERROR'),
                    __('frontendMessages.EXCEPTION_MESSAGES.INSECURE_CALL_MESSAGE'));
        }

        return view('admin.twoFactorAuth.enableTwoFactorAuth')->with('data', $twoFactorAuthData);
    }

    /**
     * Method to generated secret code and store in the DB for the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate2faSecret()
    {
        try {
            $this->twoFactorAuthService->generateTwoFactorSecretCode();
        } catch (ModelNotFoundException $exception) {
            return redirect('2fa')->with(config('frontendMessages.ERROR'),
                __('frontendMessages.EXCEPTION_MESSAGES.MODEL_NOT_FOUND_MESSAGE'));
        }

        return redirect('/2fa')->with(config('frontendMessages.SUCCESS'),
            __('frontendMessages.SUCCESS_MESSAGES.SECRET_KEY_GENERATED'));
    }

    /**
     * Method to enable the 2FA for the user.
     *
     * @param EnableTwoFactorAuthRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable2fa(EnableTwoFactorAuthRequest $request)
    {
        $result = $this->twoFactorAuthService->enableTwoFactorAuthentication($request);

        if ($result) {
            return redirect('2fa')->with(config('frontendMessages.SUCCESS'),
                __('frontendMessages.SUCCESS_MESSAGES.2FA_ENABLED'));
        }

        return redirect('2fa')->with(__('frontendMessages.ERROR'),
            __('frontendMessages.EXCEPTION_MESSAGES.INVALID_SECRET_CODE'));
    }

    /**
     * Method to disable 2FA for the user.
     *
     * @param DisableTwoFactorAuthRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable2fa(DisableTwoFactorAuthRequest $request)
    {
        $result = $this->twoFactorAuthService->disableTwoFactorAuthentication($request);

        if ($result) {
            return redirect('/2fa')->with(__('frontendMessages.SUCCESS'),
                __('frontendMessages.SUCCESS_MESSAGES.2FA_DISABLED'));
        }

        return redirect()->back()->with(__('frontendMessages.ERROR'),
            __('frontendMessages.EXCEPTION_MESSAGES.PASSWORD_INVALID'));
    }

    /**
     * Method to generate or regenerate backup codes.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function generate2FaBackupCodes()
    {
        $result = $this->twoFactorAuthService->generate2FaBackupCodes();

        if ($result) {
            return redirect('/2fa')->with(__('frontendMessages.SUCCESS'),
                __('frontendMessages.SUCCESS_MESSAGES.BACKUP_CODES_GENERATED'));
        }

        return redirect()->back()->with(__('frontendMessages.ERROR'),
            __('frontendMessages.EXCEPTION_MESSAGES.BACKUP_CODES_ERROR'));
    }

    /**
     * Method to download the backup codes for current logged in users.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function download2FaBackupCodes()
    {
        $backupCodes = $this->twoFactorAuthService->getBackupCodes(true);

        if ($backupCodes) {
            $headers = [
                'Content-type' => config('constants.BACKUP_FILE_CONTENT_TYPE'),
                'Content-Disposition' => sprintf('attachment; filename="%s"',
                    config('constants.BACKUP_CODE_FILE_NAME')),
            ];

            return Response::make($backupCodes, config('constants.HTTP_CODES.SUCCESS'), $headers);
        }

        return redirect()->back()->with(__('frontendMessages.ERROR'),
            __('frontendMessages.EXCEPTION_MESSAGES.BACKUP_CODES_DOWNLOAD_ERROR'));
    }
}
