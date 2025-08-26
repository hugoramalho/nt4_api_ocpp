<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 15/08/2025
 **/

namespace App\Event;


use App\Messenger\Ocpp\OcppEventMessage;

//#[AsMessageHandler]
final class OcppEventHandler
{
    public function __invoke(OcppEventMessage $msg): void
    {
        $event = $msg->payload;
        // mesmo switch de domínio que no exemplo A
    }
}