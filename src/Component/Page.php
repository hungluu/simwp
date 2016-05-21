<?php
namespace Simwp\Component;
use Simwp;

class Page extends Base{
	public $level  = '';
	public $slug   = 'custom-page';
	public $render = '';
	public $before = '<form action="" method="post">';
	public $after  = '</form>';

	public function __construct($name){
		$this->name = $name;
		$this->slug = Simwp::slug($name);
	}

	public function link($url){
		$this->render = 'redirect:' . $url;
		return $this;
	}
}
