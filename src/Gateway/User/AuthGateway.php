<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway\User;

use App\Exception\IdpAuthException;
use App\Gateway\AbstractGateway;
use App\Mapper\SignInInput;
use App\Service\AuthService;

final readonly class AuthGateway extends AbstractGateway
{

    public function authenticate(SignInInput $authInput): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                "{$_SERVER['IDP_BASE_URL']}/oauth",
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'X-Client-Application' => AuthService::$application,
                        'X-Fingerprint' => AuthService::$fingerprint,
                        'X-Client-Agent' => AuthService::$agent,
                        'X-Api-Key' => $_SERVER['IDP_API_KEY'] ?? null,
                    ],
                    'json' => [
                        'username' => $authInput->username,
                        'password' => $authInput->password,
                    ],
                ]
            );
            if ($response->getStatusCode() !== 200) {
                throw IdpAuthException::fromHttp(
                    "IDP auth failed (HTTP STATUS: {$response->getStatusCode()}"
                );
            }
            //
            return $response->toArray(false);

        } catch (IdpAuthException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw IdpAuthException::fromHttp('Fail to contact IDP: ' . $e->getMessage(), 0, $e);
        }
    }

    protected function serviceName(): string
    {
        // TODO: Implement serviceName() method.
    }
}
