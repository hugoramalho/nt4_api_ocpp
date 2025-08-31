<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Mapper\SignInInput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class AuthController extends BaseController
{
    #[Route('/auth', methods: ['POST'])]
    public function auth(
        #[MapRequestPayload] SignInInput $authInput,
    ): JsonResponse
    {
        $this->validateEntity($authInput);
        return new JsonResponse(
            $this->authService->authenticate($authInput),
            200
        );
    }

}
