<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UuidValidator extends ConstraintValidator
{
    private const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Uuid) {
            throw new UnexpectedTypeException($constraint, Uuid::class);
        }
        // Valores nulos ou vazios devem ser validados por NotBlank/NotNull
        if (null === $value || '' === $value) {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = trim($value);
        // Validação básica do formato
        if (!preg_match(self::UUID_PATTERN, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setCode(Uuid::INVALID_UUID_ERROR)
                ->addViolation();
            return;
        }

        // Validação estrita (versão e variante)
        if ($constraint->strict) {
            $this->validateStrict($value, $constraint);
        }
    }

    private function validateStrict(string $uuid, Uuid $constraint): void
    {
        // Extrai a versão (4º grupo, primeiro caractere)
        $version = (int)$uuid[14];
        // Extrai a variante (4º grupo, primeiro caractere)
        $variant = $uuid[19];
        // Valida versão
        if (!in_array($version, $constraint->versions, true)) {
            $this->context->buildViolation('O UUID "{{ value }}" possui uma versão inválida. Versões aceitas: {{ versions }}.')
                ->setParameter('{{ value }}', $uuid)
                ->setParameter('{{ versions }}', implode(', ', $constraint->versions))
                ->setParameter('{{ current_version }}', (string)$version)
                ->setCode(Uuid::INVALID_UUID_ERROR)
                ->addViolation();
            return;
        }
        // Valida variante (deve ser 8, 9, A, B, a, b)
        if (!in_array(strtolower($variant), ['8', '9', 'a', 'b'], true)) {
            $this->context->buildViolation('O UUID "{{ value }}" possui uma variante inválida.')
                ->setParameter('{{ value }}', $uuid)
                ->setCode(Uuid::INVALID_UUID_ERROR)
                ->addViolation();
        }
    }
}
