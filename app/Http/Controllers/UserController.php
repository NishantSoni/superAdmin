<?php

namespace App\Http\Controllers;

use App\Exceptions\SuperAdminException;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * UserController constructor.
     * Initialize all class instances.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Method to show users list.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $table = $this->userService->getAllUsers();
        } catch (\ErrorException $exception) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.SHOW_USERS_LIST'));
        }

        return view('admin.users.usersList', ['table' => $table]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.user');
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $result = $this->userService->createUser($request);

        if (!$result) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.CREATE_USER_MESSAGE'));
        }

        return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_CREATED'));
    }

    /**
     * Method to show a particular user.
     *
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userService->getUser($id);
        if ($user == null) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.FIND_USER_MESSAGE'));
        }

        return view('admin.users.user', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $result = $this->userService->updateUser($request, $id);
        if ($result == null) {
            return redirect('/users/edit')->with('errorMessage',
                config('frontendMessages.EXCEPTION_MESSAGES.UPDATE_USER_MESSAGE'));
        }

        return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_UPDATED'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->userService->deleteUser($id);

        if (!$result) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.DELETE_USER_MESSAGE'));
        }

        return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_DELETED'));
    }
}
