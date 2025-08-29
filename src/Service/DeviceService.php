<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 25/08/2025
 **/

namespace App\Service;

use App\Entity\OcppTerminal;
use App\Entity\Station;
use App\Mapper\StationInput;
use App\Mapper\OcppDeviceInput;
use Symfony\Component\Console\Terminal;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TerminalService extends BaseService
{
    /**
     * @param array $parameters
     * @return OcppTerminal[]
     */
    public function query(array $parameters = []): array
    {
        return $this->entityManager->getRepository(OcppTerminal::class)->findBy($parameters);
    }

    public function create(OcppDeviceInput $terminalInput): OcppTerminal
    {
        $this->validate($terminalInput);
        $terminal = new OcppTerminal();
        $terminal->name = $terminalInput->name;
        $terminal->protocolVersion = $terminalInput->protocolVersion;
        $terminal->station = $this->findEntity(Station::class, $terminalInput->station_id);
        $this->entityManager->persist($terminal);
        $this->entityManager->flush();
        return $terminal;
    }

    public function update(OcppDeviceInput $ocppTerminalInput, OcppTerminal $ocppTerminal): OcppTerminal
    {
        $this->validate($ocppTerminalInput);
        $ocppTerminal->name = $ocppTerminalInput->name ?? $ocppTerminal->name;
        $ocppTerminal->protocolVersion = $ocppTerminalInput->protocolVersion ?? $ocppTerminal->protocolVersion;
        $ocppTerminal->station = $this->findEntity(Station::class, $ocppTerminalInput->station_id, false) ?? $ocppTerminal->station;
        $this->entityManager->flush();
        return $ocppTerminal;
    }

    public function delete(OcppTerminal $station): OcppTerminal
    {
        $station->deleted = true;
        $this->entityManager->flush();
        return $station;
    }
}
