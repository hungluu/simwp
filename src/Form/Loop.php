<?php
namespace Simwp\Form;
use Simwp;

abstract class Loop extends Base {
	public function active($idx, $opt){
		return $idx === $opt;
	}
	public function loop($key, $options){
		$opt = Simwp::get($key);
		foreach($options as $idx => $name){
			$this->each($idx, $name, $this->active($idx, $opt), $key, $options);
		}
	}
	public function render($key, $options = true, $filter = false){
		if($options === true){
			$options = Simwp::get($key);
			if($options === null){
				$options = array();
			}
		}
		if($filter){
			$filtered = call_user_func_array($filter, array(&$key, &$options));
		}
		$this->before($key, $options);
		$this->loop($key, $options);
		$this->after($key, $options);

		return Simwp::option($key);
	}
	public function before($key, $options){}
	public function after($key, $options){}
}
