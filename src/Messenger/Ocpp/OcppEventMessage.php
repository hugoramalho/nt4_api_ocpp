<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 15/08/2025
 **/

namespace App\Messenger\Ocpp;
final class OcppEventMessage {
    public function __construct(public array $payload) {}
}

