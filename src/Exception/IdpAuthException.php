<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Exception;

use RuntimeException;
use Throwable;

class IdpAuthException extends RuntimeException
{
    public static function fromHttp(string $msg, int $statusCode = 0, ?Throwable $prev = null): self
    {
        return new self($msg, $statusCode, $prev);
    }
}
