<?php
namespace Simwp\Form;
use Simwp;

class Tags extends Input {
	public function __construct(){
		wp_enqueue_script('tagit/js' , Simwp::url( Simwp::PATH . '/extras/js/tagit.min.js'),
		array('jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'jquery-ui-widget', 'jquery-ui-position'),
		false, true);
	}

	public function render($key, $extra = ''){
		return parent::render($key, $extra . ' class="simwp-tags"');
	}
}
