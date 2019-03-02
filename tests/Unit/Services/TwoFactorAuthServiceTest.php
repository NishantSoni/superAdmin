<?php

namespace Tests\Unit;

use App\Models\TwoFactorAuth;
use App\Models\TwoFactorBackup;
use App\Services\TwoFactorAuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;
use App\Repositories\TwoFactorAuthRepository;
use PragmaRX\Google2FA\Google2FA;
use App\Repositories\TwoFactorBackupRepository;

class TwoFactorAuthServiceTest extends TestCase
{
    /**
     * @var Request $requestMock
     */
    private $requestMock;

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
     * @var TwoFactorAuthService $twoFactorAuthService
     */
    private $twoFactorAuthService;

    /**
     * To initialize objects or variables.
     */
    public function setUp()
    {
        parent::setUp();

        $this->requestMock = Mockery::mock(Request::class);
        $this->twoFactorAuthRepository = Mockery::mock(TwoFactorAuthRepository::class);
        $this->twoFactorBackupRepository = Mockery::mock(TwoFactorBackupRepository::class);
        $this->googleTwoFactor = Mockery::mock(Google2FA::class);

        $this->googleTwoFactor->shouldReceive('setAllowInsecureCallToGoogleApis')
            ->with(true);

        $this->twoFactorAuthService = new TwoFactorAuthService(
            $this->twoFactorAuthRepository,
            $this->googleTwoFactor,
            $this->twoFactorBackupRepository
        );
    }

    /**
     * @covers \App\Services\TwoFactorAuthService::getBackupCodes
     */
    public function testGetBackupCodes()
    {
        $userCollection = new User(['id' => 1, 'name' => 'admin user', 'twoFactorAuthBackups' => null ]);

        Auth::shouldReceive('user')->withNoArgs()->andReturn($userCollection);

        $backupCodes = [];

        $result = $this->twoFactorAuthService->getBackupCodes();

        $this->assertEquals($backupCodes, $result);
    }

    /**
     * @covers \App\Services\TwoFactorAuthService::getBackupCodes
     */
    public function testGetBackupCodesWhenAsStringIsTrue()
    {
        $twoFactorBackups = new TwoFactorBackup(['id' => 'ID', 'backup_code' => 'BACKUP_CODE_123']);

        $userCollection = new User(['id' => 1, 'name' => 'admin user', 'twoFactorAuthBackups' => $twoFactorBackups ]);

        Auth::shouldReceive('user')->withNoArgs()->andReturn($userCollection);

        $result = $this->twoFactorAuthService->getBackupCodes(true);

        $this->assertNotNull($result);
    }

    /**
     * @covers \App\Services\TwoFactorAuthService::getTwoFactorAuthData
     *
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function testGetTwoFactorAuthData()
    {
        $twoFactorBackups = new TwoFactorBackup(['id' => 'ID', 'backup_code' => 'BACKUP_CODE_123']);

        $userCollection = new User(['id' => 1, 'name' => 'admin user', 'twoFactorAuthBackups' => $twoFactorBackups,
            'twoFactorAuth' => null ]);

        Auth::shouldReceive('user')->withNoArgs()->andReturn($userCollection);

        $result = $this->twoFactorAuthService->getTwoFactorAuthData();

        $this->assertNotNull($result);
    }

    /**
     * @covers \App\Services\TwoFactorAuthService::getTwoFactorAuthData
     *
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function testGetTwoFactorAuthDataIfSecretIsNotNull()
    {
        $twoFactorBackups = new TwoFactorBackup(['id' => 'ID', 'backup_code' => 'BACKUP_CODE_123']);
        $twoFactorAuth = new TwoFactorAuth(['id' => 'ID', 'twoFactorAuth' => 'SECRET_KEY_12']);

        $userCollection = new User(['id' => 1, 'name' => 'admin user', 'twoFactorAuthBackups' => $twoFactorBackups,
            'twoFactorAuth' => $twoFactorAuth ]);

        Auth::shouldReceive('user')->withNoArgs()->andReturn($userCollection);

        $result = $this->twoFactorAuthService->getTwoFactorAuthData();

        $this->assertNotNull($result);
    }

    /**
     * @covers \App\Services\TwoFactorAuthService::generate2FaBackupCodes
     */
    public function testGenerateTwoFactorSecretCode()
    {
        $userId = 'USER_ID_123';
        $secretKey = 'SECRET_KEY_123';

        Auth::shouldReceive('id')->withNoArgs()->andReturn($userId);
        $this->googleTwoFactor->shouldReceive('generateSecretKey')
            ->withNoArgs()
            ->andReturn($secretKey);

        $createData = [
            'user_id' => $userId,
            'google2fa_secret' => $secretKey
        ];

        $expectedResponse = new TwoFactorAuth([
            'id' => 'TWO_FACTOR_AUTH_ID_123',
            'user_id' => 'USER_ID_123',
            'google2fa_secret' => $secretKey
        ]);

        $this->twoFactorAuthRepository->shouldReceive('create')
            ->with($createData)
            ->andReturn($expectedResponse);

        $result = $this->twoFactorAuthService->generateTwoFactorSecretCode();

        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * To destroy mockery.
     */
    protected function tearDown()
    {
        Mockery::close();
    }
}
