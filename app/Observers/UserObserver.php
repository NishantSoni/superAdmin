<?php

namespace App\Observers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * UserObserver constructor.
     *
     * @param UserService $userService
     * @param UserRepository $userRepository
     */
    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     * Method to trace the user activity, all the updated fields will be stored in the database.
     *
     * @param User $user
     * @throws \Exception
     */
    public function updating(User $user)
    {
        $userBeforeUpdated = $this->userRepository->find($user->id);
        
        $this->userService->trackUserActivity(Auth::id(),User::class, config('constants.TRACK_USER_FIELDS'),
            $userBeforeUpdated, $user);
    }
}
