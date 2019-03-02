<?php

namespace App\Services;

use App\Jobs\ProcessUserExportsJob;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ExportUserService
{

    private $userRepository;

    /**
     * AuthenticationService constructor.
     * Initialize object/instances of the classes.
     *
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Method to push the export users process in the queue.
     *
     * @return void
     */
    public function processUserExport()
    {
        ProcessUserExportsJob::dispatch(Auth::user());
    }
}
