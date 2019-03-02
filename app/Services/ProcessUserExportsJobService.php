<?php

namespace App\Services;

use App\Mail\UsersExportedEmail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\User;

class ProcessUserExportsJobService
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * Method to export all the users in the csv file.
     *
     * @return void
     */
    public function processUserExports(User $user)
    {
        $this->user = $user;

        $this->unlinkExportFileIfExist();

        Excel::create($user->id, function($excel) {
            $excel->sheet('user-sheet', function($sheet) {
                // Get users from DB by using CURSOR method.
                $users = [];
                foreach (User::with('userHistory')->cursor() as $user ) {
                    $user->user_activity = $user->userHistory->toArray();
                    $user->user_last_login = $user->userLastLoginDetails->toArray();
                    $users[] = $user->toArray();
                }

                $sheet->loadView('admin.users.usersExport', ['users' => $users]);
            });
        })->store(config('constants.USER_EXPORTED_FILE_TYPE'),
            storage_path(config('constants.USER_EXPORTED_FILE_PATH')));

        $this->notifyUserViaEmail();
    }

    /**
     * Method to check if file exist then delete it, otherwise return.
     *
     * @return bool
     */
    public function unlinkExportFileIfExist() : bool
    {
        $file = storage_path(config('constants.USER_EXPORTED_FILE_PATH'). '/' .
            $this->user->id . '.' . config('constants.USER_EXPORTED_FILE_TYPE'));
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /**
     * Method to notify the users, that "users are successfully exported!"
     *
     * @return mixed
     */
    public function notifyUserViaEmail()
    {
        return Mail::to($this->user->email)->send(new UsersExportedEmail());
    }
}
