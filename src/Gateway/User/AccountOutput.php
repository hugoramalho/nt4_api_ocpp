<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway\User;

use App\Entity\BaseEntity;
use App\Gateway\Banking\TransactionOutput;

class AccountOutput extends BaseEntity
{
    public UserOutput $user;

    /** @var TransactionOutput[]  */
    public array $transactions = [];

}
