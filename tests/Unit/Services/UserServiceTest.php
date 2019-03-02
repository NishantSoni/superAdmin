<?php

namespace Tests\Unit;

use App\Http\Requests\CreateUserRequest;
use App\Repositories\UserActivityRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Okipa\LaravelBootstrapTableList\TableList;
use Tests\TestCase;
use Mockery;

class UserServiceTest extends TestCase
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
     * @var UserActivityRepository $userActivityRepository
     */
    private $userActivityRepository;

    /**
     * @var Request $requestMock
     */
    private $requestMock;

    /**
     * @var User $userMock
     */
    private $userMock;

    /**
     * @var TableList $tableList
     */
    private $tableList;

    /**
     * Class level constants, to avoid the SONAR LINT errors.
     */
    const USER_ID = 'USER_ID_123';

    /**
     * To initialize objects or variables.
     */
    public function setUp()
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userActivityRepository = Mockery::mock(UserActivityRepository::class);
        $this->requestMock = Mockery::mock(CreateUserRequest::class);
        $this->userMock = Mockery::mock(User::class);
        $this->tableList = Mockery::mock(TableList::class)->shouldIgnoreMissing();

        $this->userService = new UserService(
            $this->userRepository,
            $this->userActivityRepository
        );
    }

    /**
     * @covers \App\Services\UserService::createUser
     */
    public function testCreateUser()
    {
        $returnedArray = [
            'password' => 'secret',
            'confirm_password' => 'secret',
            'first_name' => 'testFirstName',
            'last_name' => 'testLastName',
            'email' => 'test@mailforspam.com',
        ];

        $this->requestMock->shouldReceive('all')->withNoArgs()->andReturn($returnedArray);

        Hash::shouldReceive('make')->once()->with('secret')->andReturn('secret');

        $this->userRepository->shouldReceive('create')->withAnyArgs()->andReturn($this->userMock);
        $this->userMock->shouldReceive('send')->withAnyArgs()->andReturn();

        Mail::shouldReceive('to')
            ->withAnyArgs()
            ->andReturn($this->userMock)
            ->shouldReceive('send')
            ->withAnyArgs()
            ->andReturn(1);

        $result = $this->userService->createUser($this->requestMock);

        $this->assertNotNull($result);
    }

    /**
     * @covers \App\Services\UserService::getUser
     */
    public function testGetUser()
    {
        $userCollection = new Collection(
            new User(['id' => 1, 'name' => 'admin user'])
        );
        $this->userRepository->shouldReceive('find')
            ->with(self::USER_ID)
            ->andReturn($userCollection);

        $result = $this->userService->getUser(self::USER_ID);

        $this->assertEquals($userCollection, $result);
    }

    /**
     * @covers \App\Services\UserService::getUser
     */
    public function testGetUserIfRecordsEmpty()
    {
        $userCollection = new Collection();
        $this->userRepository->shouldReceive('find')
            ->with(self::USER_ID)
            ->andReturn($userCollection);

        $result = $this->userService->getUser(self::USER_ID);

        $this->assertSame($result, $userCollection);
    }

    /**
     * @covers \App\Services\UserService::deleteUser
     */
    public function testDeleteUserIfExist()
    {
        $this->userRepository->shouldReceive('update')
            ->with(self::USER_ID, ['is_active' => 0])
            ->andReturn(true);

        $result = $this->userService->deleteUser(self::USER_ID);

        $this->assertTrue($result);
    }

    /**
     * @covers \App\Services\UserService::deleteUser
     */
    public function testDeleteUserIfNotExist()
    {
        $this->userRepository->shouldReceive('update')
            ->with(self::USER_ID, ['is_active' => 0])
            ->andReturn(false);

        $result = $this->userService->deleteUser(self::USER_ID);

        $this->assertFalse($result);
    }

    /**
     * @covers \App\Services\UserService::updateUser
     */
    public function testUpdateUser()
    {
        $returnedArray = [
            'password' => 'secret',
            'confirm_password' => 'secret',
            'first_name' => 'nishant',
            'last_name' => 'soni',
            'email' => 'test@mailforspam.com',
        ];

        $this->requestMock->shouldReceive('all')->withNoArgs()->andReturn($returnedArray);

        $this->userRepository->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn(true);

        $result = $this->userService->updateUser($this->requestMock, self::USER_ID);

        $this->assertTrue($result);
    }

    /**
     * @covers \App\Services\UserService::updateUser
     */
    public function testUpdateUserIfUserNotFound()
    {
        $returnedArray = [
            'password' => 'secret',
            'confirm_password' => 'secret',
            'first_name' => 'nishant',
            'last_name' => 'soni',
            'email' => 'test@mailforspam.com',
        ];

        $this->requestMock->shouldReceive('all')->withNoArgs()->andReturn($returnedArray);

        $this->userRepository->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn(false);

        $result = $this->userService->updateUser($this->requestMock, self::USER_ID);

        $this->assertFalse($result);
    }

    /**
     * @covers \App\Services\UserService::trackUserActivity
     * @throws \Exception
     */
    public function testTrackUserActivityIfTrackableFieldsAreEmpty()
    {
        $dataBeforeUpdated = new User(['id'=> self::USER_ID, 'username' => 'testUserName1', 'email' => 'testEmail']);

        $dataAfterUpdated = new User(['username' => 'testUserName1', 'email' => 'testEmail']);

        $trackableFields = [];

        $result = $this->userService->trackUserActivity(self::USER_ID, User::class, $trackableFields,
            $dataBeforeUpdated, $dataAfterUpdated);

        $this->assertTrue($result);
    }

    /**
     * @covers \App\Services\UserService::trackUserActivity
     * @throws \Exception
     */
    public function testTrackUserActivityIfNoChangeInUser()
    {
        $dataBeforeUpdated = new User(['username' => 'testUserName1', 'email' => 'testEmail']);

        $dataAfterUpdated = new User(['username' => 'testUserName1', 'email' => 'testEmail']);

        $trackableFields = [];

        $result = $this->userService->trackUserActivity(self::USER_ID, User::class, $trackableFields,
            $dataBeforeUpdated, $dataAfterUpdated);

        $this->assertTrue($result);
    }

    /**
     * To destroy mockery.
     */
    protected function tearDown()
    {
        Mockery::close();
    }
}
