<?php
namespace Simwp\Form;
use Simwp;

class DateTimePicker extends Input {
	public function __construct(){
		wp_enqueue_script('jquery-datetimepicker/js', Simwp::url( Simwp::PATH . '/extras/js/jquery.datetimepicker.full.min.js'), array('jquery'), false, true);
	}

	public function render($key, $extra = ''){
		return parent::render($key, $extra . ' class="simwp-datetime-field"');
	}
}
