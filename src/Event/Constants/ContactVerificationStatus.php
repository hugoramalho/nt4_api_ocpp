<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 21/08/2025
 **/

namespace App\Event\Constants;

enum ContactVerificationStatus: int
{
    case NOT_VERIFIED = 0;
    case VERIFIED = 1;
}
