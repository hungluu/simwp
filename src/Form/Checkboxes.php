<?php
namespace Simwp\Form;

class Checkboxes extends Loop {
	public function active($idx, $opt){
		if(!is_array($opt)){
			$opt = [];
		}

		return in_array($idx, $opt);
	}
	public function each($idx, $name, $active, $key){
		if($active){
			$extra = ' checked="checked"';
		}
		else{
			$extra = '';
		}

		echo sprintf('<label class="simwp-checkbox"><input name="%s[]" value="%s" type="checkbox" %s> %s </label>', $key, $idx, $extra, $name);
	}
}
