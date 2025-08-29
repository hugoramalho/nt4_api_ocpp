<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Entity\OcppDevice;
use App\Entity\Station;
use App\Exception\ApplicationException;
use App\Mapper\DeviceQueryParams;
use App\Mapper\StationInput;
use App\Mapper\StationQueryParams;
use App\Service\AuthService;
use App\Service\StationService;
use App\Service\User\IdpAuthClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/ocpp')]
class StationController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthService $authService,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        private readonly StationService $stationService
    )
    {
        parent::__construct($entityManager, $authService, $logger, $validator, $serializer);
    }

    #[Route('/stations', methods: ['GET'], format: 'json')]
    public function query(
        #[MapQueryString]  StationQueryParams $queryParams
    ): JsonResponse
    {
        if ($queryParams->queryAll) {
            return $this->jsonResponse(
                $this->stationService->query(),
                '',
                200
            );
        }
        //
        return $this->jsonResponse(
            $this->searchPaginated(
                Station::class,
                $queryParams,
                [
                    'like' => ['name'],
                    'defaultSort' => 'name',
                    'defaultDir'  => 'asc'
                ]
            ),
            '',
            200
        );
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/stations/{id}', methods: ['GET'], format: 'json')]
    public function get(
        int $id,
        Request $request
    ): JsonResponse
    {
        return $this->jsonResponse(
            $this->findEntity(Station::class, $id),
            'Station successfully updated.',
            200
        );
    }

    #[Route('/stations', methods: ['POST'], format: 'json')]
    public function post(
        #[MapRequestPayload] StationInput $stationInput
    ): JsonResponse
    {
        return $this->jsonResponse(
            $this->stationService->create($stationInput),
            'Station successfully created.',
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
        return $this->jsonResponse(
            $this->stationService->update($stationInput, $this->findEntity(Station::class, $id)),
            'Station successfully updated.',
            201
        );
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/stations/{id}', methods: ['PUT'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $this->stationService->delete($this->findEntity(Station::class, $id));
        return $this->jsonResponse(
            [],
            'Station successfully deleted.',
            201
        );
    }

}
