<?php
namespace Simwp\Form;
use Simwp;

class Input extends Base {
	public $format = '<input id="%s" name="%s" type="%s" %s>';
	public $type   = 'text';
	public function render($key, $extra = ''){
		$opt = Simwp::get($key);

		if($opt){
			$extra .= 'value="' . $opt . '"';
		}

		echo sprintf($this->format, $this->id($key), $key, $this->type, $extra);

		return Simwp::option($key);
	}
}
