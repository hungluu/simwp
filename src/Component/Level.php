<?php
namespace Simwp\Component;
use Simwp;

class Level extends Section {
	public function appendTo($options){
		foreach($options as $option){
			if($option instanceof Option){
				$option->level = $this->name;
			}
			else{
				Simwp::option($option)->level = $this->name;
			}
		}
	}
}
