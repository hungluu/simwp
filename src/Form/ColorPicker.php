<?php
namespace Simwp\Form;

class ColorPicker extends Input {
	public function __construct(){
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}

	public function render($key, $extra = ''){
		return parent::render($key, $extra . ' class="simwp-color-field"');
	}
}
