<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 30/08/2025
 **/

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Coupon extends BaseEntity
{
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    #[ORM\Column(type: Types::STRING, length: 45, nullable: false)]
    #[Assert\NotBlank(message: "User uuid cannot be empty.")]
    #[\App\Validator\Uuid]
    public string $uuid;
}
