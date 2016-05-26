<?php
namespace Simwp\Component;
use Simwp;

class Menu extends Base{
	public $level = 'manage_options';
	public $icon  = 'admin-generic';
	public $order = 99;

	/**
	 * Create new page
	 * @param  string        $name name of page
	 * @param  option string $slug custom slug
	 * @return Simwp\Component\Page
	 */
	public function page($name, $slug = null){
		$page = new Page($name);
		$page->name = $name;
		$page->slug = $slug;

		if($page->slug === null){
			$page->slug = Simwp::slug($name);
		}

		static::append($page);

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
