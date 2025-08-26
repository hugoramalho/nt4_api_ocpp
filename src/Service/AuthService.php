<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 20/08/2025
 **/

namespace App\Service;

use App\Exception\IdpAuthException;
use App\Gateway\User\AccountOutput;
use App\Gateway\User\AuthGateway;
use App\Gateway\User\UserOutput;
use App\Mapper\AuthInput;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthService
{
    public static ?string $fingerprint = null;
    public static ?int $agent = null;
    public static ?int $application = null;
    public static ?string $userUuid = null;
    public static ?string $accountUuid = null;

    const IDP_BASE_URL = 'https://idp.example.com/';

    public function __construct(
        private readonly AuthGateway $authGateway,
    )
    {
    }

    public function authenticate(AuthInput $authInput): array
    {
        return $this->authGateway->authenticate($authInput);
    }
}
