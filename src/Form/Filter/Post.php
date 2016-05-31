<?php
namespace Simwp\Form\Filter;

class Post extends Base {
	public function filter(&$key, &$options){
		$old_options = $options;
		$options = array();

		foreach($old_options as $option){
			$options[$option->ID] = $option->post_title;
		}
	}
}
