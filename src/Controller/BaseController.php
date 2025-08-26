<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 25/08/2025
 **/

namespace App\Controller;

use App\Exception\ApplicationException;
use App\Service\AuthService;
use App\Service\User\IdpAuthClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected AuthService            $authService,
        protected LoggerInterface        $logger,
        protected ValidatorInterface     $validator
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
     * @throws ApplicationException
     */
    public function findEntity(string $class, int|string $id): ?object
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id)) {
            if (!$entity = $this->entityManager->getRepository($class)->findOneBy(['uuid' => $id])) {
                throw new ApplicationException(
                    "Entity: {$class} not found",
                );
            }
            return $entity;
        }

        if (!$entity = $this->entityManager->getRepository($class)->find($id)) {
            throw new ApplicationException(
                "Entity: {$class} not found",
                404
            );
        }
        return $entity;
    }


}
