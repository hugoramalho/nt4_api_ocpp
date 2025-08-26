<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 24/08/2025
 **/

namespace App\Mapper;

use Symfony\Component\Validator\Constraints as Assert;

class StationInput
{
    public int $id;

    #[Assert\NotBlank]
    public string $name;
}
