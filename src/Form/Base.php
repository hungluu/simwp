<?php
namespace Simwp\Form;

abstract class Base {
	public function set($key, $value){
		$this->$key = $value;
		return $this;
	}

	public function id($key){
		return 'simwp-input-' . $key;
	}
}
