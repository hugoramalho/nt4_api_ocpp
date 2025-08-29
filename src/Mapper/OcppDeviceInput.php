<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class OcppDeviceInput
{

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[SerializedName('protocol_version')]
    public string $protocolVersion;

    #[Assert\NotBlank]
    #[SerializedName('station_id')]
    public int $stationId;

}
