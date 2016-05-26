<?php
namespace Simwp\Component;
use Simwp;
use Symfony\Component\Validator\Constraint as Constraint;

class Option extends Base {
	public $type       = 'string';
	public $default    = null;
	public $validators = [];
	public $level      = 'manage_options';
	public function validate(Constraint $constraint){
		$this->validators[] = $constraint;
		return $this;
	}
	public function isValidated(){
		return count($this->validators) > 0;
	}
	public function isLimited(){
		return count($this->items) > 0;
	}
	public function isAccessible(){
		return Simwp::isAccessible($this);
	}
	public function handled($fn){
		Simwp::handled($this->name, $fn);
		return $this;
	}
	public function is($type){
		$this->type = $type;
		return $this;
	}
	public function updated($fn){
		Simwp::updated($this->name, $fn);
		return $this;
	}
}
