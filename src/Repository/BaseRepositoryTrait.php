<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Repository;

use App\Exception\ApplicationException;
use App\Mapper\BaseQueryParams;
use App\Mapper\PaginatorOutput;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ramsey\Uuid\Uuid;

trait BaseRepositoryTrait
{
    public const SAFE_MAX_PAGE_SIZE = 100; // fallback defensivo

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

    public function findOneEntityBy(string $class, array $filter = [], bool $throwException = true): ?object
    {
        $entity =  $this->entityManager->getRepository($class)->findOneBy($filter);
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

    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param BaseQueryParams $queryParams // pode ser subclasse com filtros adicionais e, opcionalmente, sort/dir
     * @param array{
     *   alias?: string,
     *   like?: string[],
     *   allowedFilters?: string[],       // por padrão: todos os fields escalares da entidade
     *   sortMap?: array<string,string>,  // ex.: ['name'=>'e.name','created_at'=>'e.createdAt']
     *   defaultSort?: string,
     *   defaultDir?: 'asc'|'desc',
     *   caseInsensitiveLike?: bool,      // default: false (prefira collation no DB)
     *   transform?: callable(T): mixed    // map entity -> array/json
     * } $options
     * @return array{meta: array<string,mixed>, data: array<int,mixed>}
     */
    public function searchPaginated(
        string $entityClass,
        BaseQueryParams $queryParams,
        array $options = []
    ): PaginatorOutput
    {
        $alias = $options['alias'] ?? 'e';
        $meta = $this->entityManager->getClassMetadata($entityClass);

        // Paginação diretamente do objeto
        $page = (int)$queryParams->page;
        if ($page < 1) {
            $page = 1;
        }

        $maxPageSize = (int) $queryParams->maxPageSize;
        if ($maxPageSize < 1) {
            $maxPageSize = self::SAFE_MAX_PAGE_SIZE;
        }

        $pageSize = (int)$queryParams->pageSize;
        if ($pageSize < 1) {
            $pageSize = 1;
        }
        if ($pageSize > $maxPageSize) {
            $pageSize = $maxPageSize;
        }

        $qb = $this->entityManager->createQueryBuilder()
            ->select($alias)
            ->from($entityClass, $alias);

        $allowedFilters = $options['allowedFilters'] ?? $meta->getFieldNames();
        $likeFields = $options['like'] ?? [];
        $caseInsensitive = (bool)($options['caseInsensitiveLike'] ?? false);

        $filters = $this->extractFilters($queryParams);
        $this->applyFilters($qb, $meta, $alias, $filters, $allowedFilters, $likeFields, $caseInsensitive);

        // Ordenação: usa sort/dir se a subclasse expuser; senão defaults
        $defaultSort = $options['defaultSort'] ?? ($meta->getIdentifier()[0] ?? 'id');
        $defaultDir = strtolower($options['defaultDir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

        /** @var string|null $sortProp */
        $sortProp = property_exists($queryParams, 'sort') ? (string)($queryParams->sort ?? '') : null;
        $sortKey = $sortProp ?: $defaultSort;

        /** @var string|null $dirProp */
        $dirProp = property_exists($queryParams, 'dir') ? (string)($queryParams->dir ?? '') : null;
        $dir = strtoupper($dirProp ?: $defaultDir);
        $dir = $dir === 'DESC' ? 'DESC' : 'ASC';

        $sortMap = $options['sortMap'] ?? [];
        $orderBy = $sortMap[$sortKey] ?? (in_array($sortKey, $meta->getFieldNames(), true) ? "$alias.$sortKey" : "$alias.$defaultSort");
        $qb->orderBy($orderBy, $dir);

        $qb->setFirstResult(($page - 1) * $pageSize)->setMaxResults($pageSize);

        $paginator = new Paginator($qb, true);
        $total = count($paginator);

        $transform = $options['transform'] ?? null;
        $data = [];
        foreach ($paginator as $entity) {
            $data[] = is_callable($transform) ? $transform($entity) : $entity;
        }

        return new PaginatorOutput(
            $page,
            $pageSize,
            $total,
            (int)ceil($total / $pageSize),
            $sortKey,
            strtolower($dir),
            $data
        );
    }

    /**
     * Extrai filtros do objeto, removendo metacampos de paginação/ordenação.
     * @return array<string,mixed>
     */
    private function extractFilters(object $queryParams): array
    {
        $skip = ['page', 'pageSize', 'maxPageSize', 'sort', 'dir'];
        $ref = new \ReflectionObject($queryParams);

        $filters = [];
        foreach ($ref->getProperties() as $prop) {
            $name = $prop->getName();
            if (in_array($name, $skip, true)) {
                continue;
            }

            $prop->setAccessible(true);
            $value = $prop->isInitialized($queryParams) ? $prop->getValue($queryParams) : null;
            if ($value === null) {
                continue;
            }

            $filters[$name] = $value;
        }

        return $filters;
    }

    /**
     * @param array<string,mixed> $filters
     */
    private function applyFilters(
        QueryBuilder  $qb,
        ClassMetadata $meta,
        string        $alias,
        array         $filters,
        array         $allowedFilters,
        array         $likeFields,
        bool          $caseInsensitive
    ): void
    {
        foreach ($filters as $key => $value) {
            if (is_string($key) === false) {
                continue;
            }
            if (in_array($key, $allowedFilters, true) === false) {
                continue;
            }
            if ($meta->hasField($key) === false) {
                continue;
            }

            $paramName = $key;
            $fieldPath = "$alias.$key";

            if (is_array($value)) {
                $qb->andWhere($qb->expr()->in($fieldPath, ":$paramName"))
                    ->setParameter($paramName, $value);
                continue;
            }

            $type = $meta->getTypeOfField($key);

            if (in_array($key, $likeFields, true) && is_string($value)) {
                if ($caseInsensitive) {
                    $qb->andWhere($qb->expr()->like("LOWER($fieldPath)", ":$paramName"))
                        ->setParameter($paramName, '%' . mb_strtolower($value) . '%');
                    continue;
                }
                $qb->andWhere($qb->expr()->like($fieldPath, ":$paramName"))
                    ->setParameter($paramName, '%' . $value . '%');
                continue;
            }

            if (in_array($type, ['integer', 'smallint', 'bigint'], true)) {
                $qb->andWhere("$fieldPath = :$paramName")->setParameter($paramName, (int)$value);
                continue;
            }

            if ($type === 'boolean') {
                $qb->andWhere("$fieldPath = :$paramName")->setParameter($paramName, filter_var($value, FILTER_VALIDATE_BOOL));
                continue;
            }

            if (in_array($type, ['carbon_immutable', 'carbon', 'date', 'datetime', 'datetimetz', 'time', 'date_immutable', 'datetime_immutable', 'datetimetz_immutable'], true)) {
                $qb->andWhere("$fieldPath = :$paramName")->setParameter($paramName, $value);
                continue;
            }

            $qb->andWhere("$fieldPath = :$paramName")->setParameter($paramName, $value);
        }
    }
}
