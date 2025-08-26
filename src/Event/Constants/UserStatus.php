<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 20/08/2025
 **/

namespace App\Event\Constants;

use App\Entity\EnumTrait;

enum UserStatus: int
{
    use EnumTrait;

    case ACTIVE = 1;
    case INACTIVE = 2;
    case BLOCKED = 3;
    case REMOVED = 4;
}
