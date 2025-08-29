<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 28/08/2025
 **/

namespace App\Repository;

use App\Entity\Station;
use App\Mapper\StationQueryParams;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class StationRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function searchPaginated(StationQueryParams $queryParams): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Station::class, 's');

        // Filtros (somente campos da própria entidade, sem joins):
        if (null !== $queryParams->id) {
            $qb->andWhere('s.stationId = :stationId')
                ->setParameter('stationId', $queryParams->id);
        }

        if (null !== $queryParams->name) {
            // Caso o collation do banco já seja case-insensitive, prefira não usar LOWER()
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', '%'.$queryParams->name.'%');
        }

//        if (null !== $queryParams->protocolVersion) {
//            $qb->andWhere('s.protocolVersion = :pv')
//                ->setParameter('pv', $queryParams->protocolVersion);
//        }

        // Whitelist de ordenação para evitar injeção em DQL:
        $sortMap = [
            'name'        => 's.name',
            'id'  => 's.id',
            'created_at'  => 's.createdAt',
        ];
        $orderBy = $sortMap[$queryParams->sort ?? 'name'] ?? 's.name';
        $dir     = strtoupper($queryParams->dir ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

        $qb->orderBy($orderBy, $dir);

        // Paginação
        $page    = max(1, $queryParams->page);
        $perPage = min(100, max(1, $queryParams->pageSize));
        $qb->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($qb, true); // fetchJoinCollection=false aqui, pois não há joins
        $total     = count($paginator);

        // Extraia os itens
        $items = [];
        foreach ($paginator as $entity) {
            $items[] = [
                'id'              => $entity->getId(),
                'station_id'      => $entity->getStationId(),
                'name'            => $entity->getName(),
                'protocolVersion' => $entity->getProtocolVersion(),
                'created_at'      => $entity->getCreatedAt()?->format(DATE_ATOM),
            ];
        }

        return [
            'pagination' => [
                'page'       => $page,
                'page_size'   => $perPage,
                'total'      => $total,
                'total_pages' => (int) ceil($total / $perPage),
                'sort'       => $queryParams->sort,
                'dir'        => strtolower($dir),
            ],
            'data' => $items,
        ];
    }
}
