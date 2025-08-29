<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 29/08/2025
 **/

namespace App\Mapper;

class ResponseOutput
{
    public function __construct(
        public readonly ?array           $data,
        public readonly ?string          $message,
        public readonly ?PaginatorOutput $pagination = null,
        public ?bool                     $success = null,
        public ?array                    $errors = null
    )
    {
    }

}
