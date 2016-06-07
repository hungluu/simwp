<?php
namespace Simwp\Form;

class Options extends Loop {
	public function before($key, $options){
		echo sprintf('<select id="%s" name="%s">', $this->id($key), $key);
	}

	public function after($key, $options){
		echo '</select>';
	}

	public function each($idx, $name, $active, $key){

		if($active){
			$extra = ' selected="selected"';
		}
		else{
			$extra = '';
		}

		echo sprintf('<option value="%s"%s>%s</option>', $idx, $extra, $name);
	}
}
