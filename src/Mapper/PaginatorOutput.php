<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 29/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Serializer\Attribute\SerializedName;

class PaginatorOutput
{
    public function __construct(
        public readonly int    $page,
        #[SerializedName('page_size')]
        public readonly int    $pageSize,
        public readonly int    $total,
        #[SerializedName('total_pages')]
        public readonly int    $count,
        public readonly string $sort,
        public readonly string $dir,
        public readonly array  $items
    )
    {
    }


}
