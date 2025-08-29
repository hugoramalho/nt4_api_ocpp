<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 28/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Serializer\Attribute\SerializedName;

class BaseQueryParams
{
    public readonly int $page;

    #[SerializedName('page_size')]
    public readonly int $pageSize;
    #[SerializedName('max_page_size')]
    public readonly int $maxPageSize;

}
