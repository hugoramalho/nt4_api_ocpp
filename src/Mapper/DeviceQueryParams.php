<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 28/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
class DeviceQueryParams extends BaseQueryParams
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $pageSize = 20,
        public readonly int $maxPageSize = 100,
        public readonly ?string $name = null,
        public readonly ?string $uuid = null,
        #[SerializedName('protocol_version')]
        public readonly ?string $protocolVersion = null,
    )
    {

    }

}
