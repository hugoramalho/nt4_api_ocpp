<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Entity\Station;
use App\Exception\ApplicationException;
use App\Mapper\StationInput;
use App\Service\StationService;
use App\Service\User\IdpAuthClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/ocpp')]
class StationController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        IdpAuthClient $idpAuthClient,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        private StationService $stationService,
    )
    {
        parent::__construct($entityManager, $idpAuthClient, $logger, $validator);
    }

    #[Route('/stations', methods: ['GET'], format: 'json')]
    public function get(Request $request): JsonResponse
    {
        return new JsonResponse(['teste']);
    }

    #[Route('/stations', methods: ['POST'], format: 'json')]
    public function post(
        #[MapRequestPayload] StationInput $stationInput
    ): JsonResponse
    {
        $station = $this->stationService->create($stationInput);
        return new JsonResponse([
            'success' => true,
            'data' => $station
        ],
            201
        );
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/stations/{id}', methods: ['PUT'], format: 'json')]
    public function put(
        int                               $id,
        #[MapRequestPayload] StationInput $stationInput
    ): JsonResponse
    {
        $station = $this->stationService->update($stationInput, $this->findEntity(Station::class, $id));
        return new JsonResponse([
            'success' => true,
            'data' => $station
        ],
            200
        );
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/stations/{id}', methods: ['PUT'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $this->stationService->delete($this->findEntity(Station::class, $id));
        return new JsonResponse([
            'success' => true
        ],
            200
        );
    }

}
