<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 28/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class StationQueryParams extends BaseQueryParams
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $pageSize = 20,
        public readonly int $maxPageSize = 100,
        public readonly bool $queryAll = false,
        public readonly ?string $name = null
    )
    {
    }

}

