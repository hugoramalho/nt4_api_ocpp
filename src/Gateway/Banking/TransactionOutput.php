<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 26/08/2025
 **/

namespace App\Gateway\Banking;

use Symfony\Component\Validator\Constraints as Assert;

class TransactionOutput
{
    #[Assert\NotBlank(message: "User uuid cannot be empty.")]
    #[\App\Validator\Uuid]
    public string $uuid;

    public Account $account;

    public Wallet $payerWallet;

    public Wallet $receiverWallet;

    public float $amount = 0;
}

class TransactionStatus
{

}

