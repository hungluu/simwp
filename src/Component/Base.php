<?php
namespace Simwp\Component;

abstract class Base {
	/**
	 * Component's name
	 * @var string
	 */
	public $name  = 'Component';
	/**
	 * Array of items belongs to this component
	 * @var array
	 */
	public $items = array();

	/**
	 * Create a component requires its name
	 * @param string $name Name of component
	 */
	public function __construct ($name) {
		$this->name = $name;
	}

	/**
	 * Append to another component
	 * @param  Simwp\Component\Base $parent the component that this component will belongs to
	 * @return Simwp\Component\Base 		this component
	 */
	public function appendTo (Base $parent) {
		$parent->append($this);
		return $this;
	}

	/**
	 * Append another component
	 * @param  mixed $child thing that this component will append
	 * @return Simwp\Component\Base 	   this component
	 */
	public function append ($child) {
		$this->items[] = $child;
		return $this;
	}

	/**
	 * Set a component property's value, for methods chaining
	 * @param  string				$key
	 * @param  mixed				$val
	 * @return Simwp\Component\Base
	 */
	public function set ($key, $val) {
		$this->$key = $val;
		return $this;
	}

	/**
	 * @alias  set
	 *
	 * For fast setting properties
	 */
	public function __call ($key, $args) {
		return $this->set($key, $args[0]);
	}
}
