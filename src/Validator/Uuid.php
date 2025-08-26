<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Uuid extends Constraint
{
    public const INVALID_UUID_ERROR = '9b3dbcf2-5f5c-4f6a-94c3-1a2c3d4e5f6a';

    public $message = 'O valor "{{ value }}" não é um UUID válido.';
    public $versions = [1, 3, 4, 5]; // Versões aceitas
    public $strict = true; // Se deve validar versão e variante

    public function __construct(
        ?array  $versions = null,
        ?bool   $strict = null,
        ?string $message = null,
        ?array  $groups = null,
                $payload = null
    )
    {
        parent::__construct([], $groups, $payload);

        $this->versions = $versions ?? $this->versions;
        $this->strict = $strict ?? $this->strict;
        $this->message = $message ?? $this->message;
    }

    public function getDefaultOption(): string
    {
        return 'versions';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
