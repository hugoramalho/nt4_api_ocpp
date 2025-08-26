<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Service;

use App\Entity\Station;
use App\Mapper\StationInput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StationService extends BaseService
{

    public function create(StationInput $stationInput): Station
    {
        $this->validate($stationInput);
        $station = new Station();
        $station->name = $stationInput->name;
        $this->entityManager->persist($station);
        $this->entityManager->flush();
        return $station;
    }

    public function update(StationInput $stationInput, Station $station): Station
    {
        $this->validate($stationInput);
        if(!$station = $this->entityManager->getRepository(Station::class)->find($stationInput->id)) {
            throw new NotFoundHttpException('Station not found');
        }
        $station->name = $station->name ?? $stationInput->name;
        $this->entityManager->flush();
        return $station;
    }

    public function delete(Station $station): Station
    {
        $station->deleted = true;
        $this->entityManager->flush();
        return $station;
    }

}
