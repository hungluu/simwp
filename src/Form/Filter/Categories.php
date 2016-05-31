<?php
namespace Simwp\Form\Filter;

class Categories extends Base {
	public function filter(&$key, &$options){
		$old_options = $options;
		$options = array();

		foreach($old_options as $option){
			$options[$option->cat_ID] = $option->name;
		}
	}
}
