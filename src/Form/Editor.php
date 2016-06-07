<?php
namespace Simwp\Form;
use Simwp;

class Editor extends Base {
	public function render($key, $extra = ''){
		$opt = Simwp::get($key);

		wp_editor(stripslashes($opt), $this->id($key), $settings = array(
			'textarea_name' => $key
		));

		return Simwp::option($key);
	}
}
