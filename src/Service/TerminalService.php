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
use App\Mapper\OcppTerminalInput;
use Symfony\Component\Console\Terminal;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TerminalService extends BaseService
{
    public function create(OcppTerminalInput $terminalInput): OcppTerminal
    {
        $this->validate($terminalInput);
        $terminal = new OcppTerminal();
        $terminal->name = $terminalInput->name;
        $terminal->protocolVersion = $terminalInput->protocolVersion;
        $this->entityManager->persist($terminal);
        $this->entityManager->flush();
        return $terminal;
    }

    public function update(OcppTerminalInput $ocppTerminalInput, OcppTerminal $ocppTerminal): OcppTerminal
    {
        $this->validate($ocppTerminalInput);
        if(!$station = $this->entityManager->getRepository(Station::class)->find($ocppTerminalInput->id)) {
            throw new NotFoundHttpException('Station not found');
        }
        $station->name = $station->name ?? $ocppTerminal->name;
        $this->entityManager->flush();
        return $station;
    }

    public function delete(OcppTerminal $station): OcppTerminal
    {
        $station->deleted = true;
        $this->entityManager->flush();
        return $station;
    }
}
