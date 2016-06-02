<?php
namespace Simwp\Component;
use Simwp;

class Section {
	public $class = '';

	public function __construct($class){
		$this->class = $class;
	}

	public function access(){
		$args = func_get_args();
		foreach($args as $arg){
			if(is_string($arg)){
				Simwp::option($arg)->append($this->class);
			}
			else{
				$arg->append($this->class);
			}
		}
	}
}
