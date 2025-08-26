<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway\Banking;

use App\Gateway\User\AccountOutput;

class WalletOutput
{
    public AccountOutput $account;

    public float $balance = 0;

    public float $debt = 0;

    public float $credit = 0;
}
