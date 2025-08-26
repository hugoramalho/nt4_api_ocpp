<?php

/**
 * Created by: Hugo Ramalho <hugo.ramalho@gmail.com>
 *
 * Created at: 13/09/2023
 **/

namespace App\Entity;

trait EnumTrait
{
    public static function toArray(): array
    {
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }
        return $array;
    }
}
