<?php
namespace Simwp\Form;
use Simwp;

class Toggle extends Input {
	public $type = 'checkbox';
	public function active($key){
		return Simwp::get($key) === 'on' ? ' checked="checked"' : '';
	}
	public function render($key, $extra = ''){
		echo sprintf($this->format, $this->id($key), $key, $this->type, $this->active($key));
	}
}
