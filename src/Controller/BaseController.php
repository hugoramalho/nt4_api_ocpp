<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 25/08/2025
 **/

namespace App\Controller;

use App\Repository\BaseRepositoryTrait;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    use BaseRepositoryTrait;

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

}
