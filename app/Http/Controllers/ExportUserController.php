<?php
namespace App\Http\Controllers;

use App\Services\ExportUserService;
use Illuminate\Support\Facades\Auth;

class ExportUserController extends Controller
{
    /**
     * @var ExportUserService $exportUserService
     */
    private $exportUserService;

    /**
     * UserController constructor.
     * Initialize all class instances.
     *
     * @param ExportUserService $exportUserService
     */
    public function __construct(ExportUserService $exportUserService)
    {
        $this->exportUserService = $exportUserService;
    }

    /**
     * Method to export users.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportUsers()
    {
        $this->exportUserService->processUserExport();

        return redirect('/users')->with('successMessage',
            __('frontendMessages.SUCCESS_MESSAGES.USERS_EXPORTED'));
    }

    /**
     * Method to show downloadable link to users for exporting users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUsersDownload()
    {
        $pathToFile = storage_path(config('constants.USER_EXPORTED_FILE_PATH'). '/' .
            Auth::id() . '.' . config('constants.USER_EXPORTED_FILE_TYPE'));

        return view('admin.users.userFileDownloads', ['pathToFile' => $pathToFile]);
    }

    /**
     * Method will allow user to download a file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadUsers()
    {
        $pathToFile = storage_path(config('constants.USER_EXPORTED_FILE_PATH'). '/' .
            Auth::id() . '.' . config('constants.USER_EXPORTED_FILE_TYPE'));

        return response()->download($pathToFile, now() . '.' . config('constants.USER_EXPORTED_FILE_TYPE'));
    }
}
