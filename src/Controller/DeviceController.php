<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Entity\OcppTerminal;
use App\Entity\Station;
use App\Exception\ApplicationException;
use App\Mapper\OcppTerminalInput;
use App\Service\AuthService;
use App\Service\TerminalService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/ocpp')]
class TerminalController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthService $authService,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        private TerminalService $terminalService,
    )
    {
        parent::__construct($entityManager, $authService, $logger, $validator, $serializer);
    }

    #[Route('/devices', methods: ['GET'], format: 'json')]
    public function query(Request $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $this->terminalService->query(
                $request->query->all()
            )
        ]);
    }
    /**
     * @throws ApplicationException
     */
    #[Route('/devices/{uuid}', methods: ['GET'], format: 'json')]
    public function get(
        string $uuid,
        Request $request
    ): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $this->findEntity(OcppTerminal::class, $uuid)
        ]);
    }

    #[Route('/devices', methods: ['POST'], format: 'json')]
    public function post(
        #[MapRequestPayload] OcppTerminalInput $terminalInput,
    ): JsonResponse
    {
        $terminal = $this->terminalService->create($terminalInput);
        return $this->jsonResponse(
            $terminal,
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
        #[MapRequestPayload] OcppTerminalInput $terminalInput
    ): JsonResponse
    {
        $terminal = $this->terminalService->update(
            $terminalInput,
            $this->findEntity(OcppTerminal::class, $id)
        );
        return new JsonResponse([
            'success' => true,
            'data' => $terminal
        ],
            200
        );
    }


}
