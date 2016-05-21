<?php
namespace Simwp\Form;

class Radios extends Loop {
	public function before(){}
	public function after(){}

	public function each($idx, $name, $active, $key){
		if($active){
			$extra = ' checked="checked"';
		}
		else{
			$extra = '';
		}

		echo sprintf('<label class="simwp-radio"><input name="%s" value="%s" type="radio" %s> %s </label>', $key, $idx, $extra, $name);
	}
}
