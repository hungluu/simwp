<?php
namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ColorValidator extends ConstraintValidator {
	public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^#[a-f0-9]{6}|[a-f0-9]{3}$/i', $value, $matches)) {
            $this->context
				->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
