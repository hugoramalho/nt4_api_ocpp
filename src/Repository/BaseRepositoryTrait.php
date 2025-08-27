<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Repository;

use App\Exception\ApplicationException;
use Ramsey\Uuid\Uuid;

trait BaseRepositoryTrait
{
    /**
     * @template T of object
     * @param class-string<T> $class
     * @param int|string $id
     * @param bool $throwException
     * @return object|null
     * @throws ApplicationException
     */
    public function findEntity(string $class, int|string $id, bool $throwException = true): ?object
    {
        $repository = $this->entityManager->getRepository($class);
        // 1) Se for UUID (string no formato UUID), procura por coluna 'uuid'
        if (\is_string($id) && $this->isUuid($id)) {
            // Se o campo 'uuid' na Entity for mapeado como Ramsey Uuid (uuid/uuid_binary),
            // descomentar a linha abaixo para enviar o objeto Uuid ao findOneBy:
            // $id = Uuid::fromString($id);
            $entity = $repository->findOneBy(['uuid' => $id]);
            if (!$entity && $throwException) {
                throw new ApplicationException("Entity: {$class} not found", 404);
            }
            return $entity ?: null;
        }
        // 2) Caso contrário, trata como ID numérico (aceita "123" como 123)
        $numericId = \is_int($id) ? $id : (\ctype_digit((string)$id) ? (int)$id : $id);
        $entity = $repository->find($numericId);
        if (!$entity && $throwException) {
            throw new ApplicationException("Entity: {$class} not found", 404);
        }
        return $entity ?: null;
    }

    private function isUuid(string $value): bool
    {
        if (\class_exists(Uuid::class)) {
            return Uuid::isValid($value);
        }
        // Alternativa genérica (RFC 4122; aceita v1..v8, sem forçar versão)
        return (bool)\preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
            $value
        );
    }
}
