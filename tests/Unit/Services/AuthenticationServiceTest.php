<?php

namespace Tests\Unit;

use App\Repositories\AuthenticationLogRepository;
use App\Services\AuthenticationService;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class AuthenticationServiceTest extends TestCase
{
    /**
     * @var AuthenticationLogRepository $authenticationLogRepository
     */
    private $authenticationLogRepository;

    /**
     * @var AuthenticationService $authenticationService
     */
    private $authenticationService;

    /**
     * @var Request $requestMock
     */
    private $requestMock;

    /**
     * To initialize objects or variables.
     */
    public function setUp()
    {
        parent::setUp();

        $this->requestMock = Mockery::mock(Request::class);
        $this->authenticationLogRepository = Mockery::mock(AuthenticationLogRepository::class);
        $this->authenticationService = new AuthenticationService($this->authenticationLogRepository);
    }

    /**
     * @covers \App\Services\AuthenticationService::storeLoginActivityOfUser
     */
    public function testStoreLoginActivityOfUser()
    {
        $ipAddress = $this->requestMock->shouldReceive('ip')->withNoArgs('IP_ADDRESS');
        $user = new User(['id' => 'USER_ID_123']);

        $logDetails = [
            'user_id' => $user->id,
            'ip_address' => $ipAddress
        ];

        $this->authenticationLogRepository->shouldReceive('create')
            ->withAnyArgs($logDetails);

        $this->authenticationService->storeLoginActivityOfUser($this->requestMock, $user);

        $this->assertTrue(true);
    }

    /**
     * To destroy mockery.
     */
    protected function tearDown()
    {
        Mockery::close();
    }
}
