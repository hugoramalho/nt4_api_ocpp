<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ocpp_vehicles")]
class Vehicle extends BaseEntity
{
    #[ORM\Column(type: Types::STRING, length: 10, nullable: false)]
    public string $plateNumber;

}

