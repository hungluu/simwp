<?php
namespace Simwp\Form;
use Simwp;

class Tags extends Input {
	public function __construct(){
		wp_enqueue_script('jquery.caret/js' , Simwp::url( Simwp::PATH . '/extras/js/jquery.caret.min.js'),
		array('jquery'), false, true);
		wp_enqueue_script('jquery.tag-editor/js' , Simwp::url( Simwp::PATH . '/extras/js/jquery.tag-editor.min.js'),
		array('jquery.caret/js'), false, true);
	}

	public function render($key, $extra = ''){
		return parent::render($key, $extra . ' class="simwp-tags"');
	}
}
