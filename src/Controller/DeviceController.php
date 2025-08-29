<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Entity\OcppDevice;
use App\Exception\ApplicationException;
use App\Mapper\BaseQueryParams;
use App\Mapper\DeviceQueryParams;
use App\Mapper\OcppDeviceInput;
use App\Mapper\ResponseOutput;
use App\Service\AuthService;
use App\Service\DeviceService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/ocpp')]
class DeviceController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthService            $authService,
        LoggerInterface        $logger,
        ValidatorInterface     $validator,
        SerializerInterface    $serializer,
        private DeviceService  $deviceService,
    )
    {
        parent::__construct($entityManager, $authService, $logger, $validator, $serializer);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/devices', methods: ['GET'], format: 'json')]
    public function query(
        #[MapQueryString]  DeviceQueryParams $queryParams
    ): JsonResponse
    {
        if ($queryParams->queryAll) {
            return $this->jsonResponse(
                $this->deviceService->query(),
                '',
                200
            );
        }
        //
        return $this->jsonResponse(
            $this->searchPaginated(
                OcppDevice::class,
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

//    #[Route('/devices', methods: ['GET'], format: 'json')]
//    public function query(
//        #[MapQueryString]  BaseQueryParams $queryParams,
//        Request $request
//    ): JsonResponse
//    {
//        return $this->jsonResponse(
//            $this->searchPaginated(
//                OcppDevice::class,
//                $queryParams,
//                [
//                    'like' => ['name'],
//                    'sortMap' => ['name' => 'e.name', 'stationId' => 'e.stationId', 'createdAt' => 'e.createdAt'],
//                    'defaultSort' => 'name',
//                    'defaultDir'  => 'asc',
//                    'transform' => static fn(OcppDevice $object) => [
//                        'id' => $object->id,
//                        'station_id' => $object->getStationId(),
//                        'name' => $object->getName(),
//                        'protocol_version' => $object->getProtocolVersion(),
//                        'created_at' => $object->getCreatedAt()?->format(DATE_ATOM),
//                    ],
//                ]
//            ),
//            '',
//            200
//        );
//    }

    /**
     * @throws ApplicationException
     * @throws ExceptionInterface
     */
    #[Route('/devices/{uuid}', methods: ['GET'], format: 'json')]
    public function get(
        string $uuid,
        Request $request
    ): JsonResponse
    {
        return $this->jsonResponse(
            $this->findEntity(OcppDevice::class, $uuid),
            '',
            201
        );
    }

    #[Route('/devices', methods: ['POST'], format: 'json')]
    public function post(
        #[MapRequestPayload] OcppDeviceInput $terminalInput,
    ): JsonResponse
    {
        return $this->jsonResponse(
            $this->deviceService->create($terminalInput),
            'Device successfully created.',
            201
        );
    }

    /**
     * @throws ApplicationException
     */
    #[Route('/devices/{id}', methods: ['PUT'], format: 'json')]
    public function put(
        int                                    $id,
        #[MapRequestPayload] OcppDeviceInput $terminalInput
    ): JsonResponse
    {
        return $this->jsonResponse(
            $this->deviceService->update(
                $terminalInput,
                $this->findEntity(OcppDevice::class, $id)
            ),
            'Device successfully updated.',
            201
        );
    }


}
