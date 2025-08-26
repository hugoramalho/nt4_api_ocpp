<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Controller;

use App\Exception\ApplicationException;
use App\Mapper\OcppTerminalInput;
use App\Mapper\StationInput;
use App\Service\AuthService;
use App\Service\TerminalService;
use App\Service\User\IdpAuthClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1')]
class TerminalController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        IdpAuthClient $idpAuthClient,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        private TerminalService $terminalService,
    )
    {
        parent::__construct($entityManager, $idpAuthClient, $logger, $validator);
    }

    #[Route('/terminals', methods: ['POST'], format: 'json')]
    public function post(
        #[MapRequestPayload] OcppTerminalInput $terminalInput,
    ): JsonResponse
    {
        $station = $this->terminalService->create($terminalInput);
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
    #[Route('/terminals/{id}', methods: ['PUT'], format: 'json')]
    public function put(
        int                                    $id,
        #[MapRequestPayload] OcppTerminalInput $terminalInput
    ): JsonResponse
    {
        $terminal = $this->terminalService->update(
            $terminalInput,
            $this->findEntity(OcppTerminalInput::class, $id)
        );
        return new JsonResponse([
            'success' => true,
            'data' => $terminal
        ],
            200
        );
    }


}
