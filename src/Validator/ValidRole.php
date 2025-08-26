<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/


namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ValidRole extends Constraint
{
    public string $message = 'O valor "{{ value }}" não é um role válido.';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
