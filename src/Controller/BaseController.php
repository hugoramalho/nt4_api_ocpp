<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 25/08/2025
 **/

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Repository\BaseRepository;
use App\Repository\BaseRepositoryTrait;
use App\Application\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    use BaseRepositoryTrait;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected AuthService            $authService,
        protected LoggerInterface        $logger,
        protected ValidatorInterface     $validator,
        protected SerializerInterface    $serializer
    )
    {
    }

    public function validateEntity(object $object, ?array $groups = null): void
    {
        $violations = $this->validator->validate($object, null, $groups);
        if ($violations->count() > 0) {
            throw new ValidationFailedException($object, $violations);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function jsonResponse(
        mixed       $data = null,
        ?string     $message = null,
        int         $statusCode = 200,
        array       $headers = []
    ): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize([
                'success' => ($statusCode >= 200 && $statusCode < 300),
                'message' => $message,
                'data' => $data
            ],
                'json'
            ),
            $statusCode,
            $headers,
            true
        );
    }

}


