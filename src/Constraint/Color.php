<?php
namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Color extends Constraint {
	public $message = 'This value is not a valid hex color';
}
