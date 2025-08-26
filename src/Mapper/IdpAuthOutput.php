<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Mapper;

use Carbon\Carbon;

final class IdpAuthOutput
{
    public function __construct(
        public readonly string              $jwt,
        public readonly ?Carbon $expiresAt = null,
        public readonly array               $rawPayload = [],
    )
    {
    }
}
