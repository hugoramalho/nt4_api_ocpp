<?php
/**
 * Created by: Hugo Ramalho <ramalho.hg@gmail.com>
 *
 * Created at: 22/08/2025
 **/

namespace App\Validator;

use App\Entity\Role;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidRoleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        $validRoles = [
            Role::ADMIN,
            Role::MANAGER,
            Role::CUSTOMER,
        ];

        if (!in_array($value, $validRoles, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', (string)$value)
                ->addViolation();
        }
    }
}
