<?php
namespace Simwp\Component;

class Menu extends Base{
	public $level = 'manage_options';
	public $icon  = 'admin-generic';
	public $order = 99;

	public function page($name){
		$page = $this->items[] = new Page($name);
		return $page;
	}

	public function first(){
		$this->order = 0;
		return $this;
	}

	public function last(){
		$this->order = 9999;
		return $this;
	}
}
