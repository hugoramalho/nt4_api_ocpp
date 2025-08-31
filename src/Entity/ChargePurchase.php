<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "ocpp_charge_purchase")]
class ChargePurchase extends BaseEntity
{
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    #[ORM\Column(type: Types::STRING, length: 45, nullable: false)]
    #[Assert\NotBlank(message: "User uuid cannot be empty.")]
    #[\App\Validator\Uuid]
    public string $uuid;

    #[ORM\ManyToOne(targetEntity: OcppDevice::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'ocpp_terminal_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    #[Ignore]
    public OcppDevice $terminal;

    #[ORM\ManyToOne(targetEntity: OcppDevice::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'ocpp_terminal_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    #[Ignore]
    public Coupon $coupon;

    #[ORM\Column(name: 'banking_transaction_uuid', type: 'string', nullable: true)]
    #[Ignore]

    public string $purchaseTransactionUuid;

    #[ORM\Column(name: 'idp_account_uuid', type: 'string', nullable: true)]
    #[SerializedName('account_uuid')]
    public string $accountUuid;
}
