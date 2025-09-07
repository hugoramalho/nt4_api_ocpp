<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 20/08/2025
 **/

namespace App\Application;

use App\Repository\BaseRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseService
{
    use BaseRepositoryTrait;
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected LoggerInterface $logger,
        protected ValidatorInterface $validator
    )
    {
    }

    public function validate(object $object, ?array $groups = null): void
    {
        $violations = $this->validator->validate($object, null, $groups);
        if ($violations->count() > 0) {
            throw new ValidationFailedException($object, $violations);
        }
    }


}
