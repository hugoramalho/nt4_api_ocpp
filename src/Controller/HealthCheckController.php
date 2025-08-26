<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 20/08/2025
 **/

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    #[Route('/api/v1/ocpp/healthcheck', name: 'healthcheck', methods: ['GET'], format: 'json')]
    public function get(Request $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Charge Point API healthcheck OK',
            ],
        200
        );
    }


}
