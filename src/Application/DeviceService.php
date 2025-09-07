<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 25/08/2025
 **/

namespace App\Service;

use App\Entity\OcppDevice;
use App\Entity\Station;
use App\Mapper\StationInput;
use App\Mapper\OcppDeviceInput;
use Symfony\Component\Console\Terminal;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeviceService extends BaseService
{
    /**
     * @param array $parameters
     * @return OcppDevice[]
     */
    public function query(array $parameters = []): array
    {
        return $this->entityManager->getRepository(OcppDevice::class)->findBy($parameters);
    }

    public function create(OcppDeviceInput $terminalInput): OcppDevice
    {
        $this->validate($terminalInput);
        $terminal = new OcppDevice();
        $terminal->name = $terminalInput->name;
        $terminal->protocolVersion = $terminalInput->protocolVersion;
        $terminal->station = $this->findEntity(Station::class, $terminalInput->stationId);
        $this->entityManager->persist($terminal);
        $this->entityManager->flush();
        return $terminal;
    }

    public function update(OcppDeviceInput $OcppDeviceInput, OcppDevice $OcppDevice): OcppDevice
    {
        $this->validate($OcppDeviceInput);
        $OcppDevice->name = $OcppDeviceInput->name ?? $OcppDevice->name;
        $OcppDevice->protocolVersion = $OcppDeviceInput->protocolVersion ?? $OcppDevice->protocolVersion;
        $OcppDevice->station = $this->findEntity(Station::class, $OcppDeviceInput->stationId, false) ?? $OcppDevice->station;
        $this->entityManager->flush();
        return $OcppDevice;
    }

    public function delete(OcppDevice $station): OcppDevice
    {
        $station->deleted = true;
        $this->entityManager->flush();
        return $station;
    }
}
