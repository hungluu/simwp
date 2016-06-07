<?php
namespace Simwp\Form;
use Simwp;

class DatePicker extends Input {
	public function __construct(){
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		$locale = get_locale();
		if($locale && $locale != 'en_US'){
			wp_enqueue_script('jquery-ui-datepicker/locale' , Simwp::url( Simwp::PATH . '/extras/jquery-ui/datepicker-locales/datepicker-' . $locale . '.js'), array('jquery-ui-datepicker'), false, true);
		}
	}

	public function render($key, $extra = ''){
		return parent::render($key, $extra . ' class="simwp-date-field"');
	}
}
