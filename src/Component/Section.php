<?php
namespace Simwp\Component;
use Simwp;

class Section extends Base{
	public function append($options){
		foreach($options as $option){
			if($option instanceof Option){
				$option->appendTo($this->name);
			}
			else{
				Simwp::option($option)->appendTo($this->name);
			}
		}
	}
}
