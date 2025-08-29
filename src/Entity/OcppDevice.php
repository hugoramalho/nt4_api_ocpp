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
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "ocpp_terminals")]
class OcppDevice extends BaseEntity
{
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    #[ORM\Column(type: Types::STRING, length: 45, nullable: false)]
    #[Assert\NotBlank(message: "User uuid cannot be empty.")]
    #[\App\Validator\Uuid]
    public string $uuid;

    #[ORM\ManyToOne(targetEntity: Station::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'ocpp_station_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    public Station $station;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    public string $name;

    #[ORM\Column(name: 'protocol_version', type: 'string', length: 10, nullable: false)]
    #[SerializedName('protocol_version')]
    public string $protocolVersion;

}

class ProtocolVersion
{
    const V_1_6 = '1.6';
    const V_2_0 = '2.0';
}
