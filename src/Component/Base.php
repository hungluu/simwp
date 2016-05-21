<?php
namespace Simwp\Component;

abstract class Base {
	public $name  = 'Component';
	public $items = [];

	public function __construct ($name) {
		$this->name = $name;
	}

	public function appendTo ($parent) {
		$parent->append($this);
		return $this;
	}

	public function append ($child) {
		$this->items[] = $child;
		return $this;
	}

	public function set ($key, $val) {
		$this->$key = $val;
		return $this;
	}
}
