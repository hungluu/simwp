<?php
namespace Simwp;
/**
 * A base class for sections
 *
 * Should only be used automatically by Simwp
 */
abstract class Section {
	/**
	 * Get a filter
	 * @param  string $name name of filter
	 * @return Simwp\Form\Filter\Base
	 */
	protected function filter ($name) {
		$class = 'Simwp\\Form\\Filter\\' . ucfirst($name);
		$filter= new $class();
		return array($filter, 'filter');
	}

	/**
	 * Make an element controller from core controllers
	 * @param  string $name name of controller
	 * @return Simwp\Form\Base
	 */
	protected function view ($name) {
		$class = 'Simwp\\Form\\' . ucfirst($name);
		return new $class();
	}

	/**
	 * @protected
	 * Alias of self::base($className)->render
	 * @param  string $name name of controller
	 * @param  array  $args argument list
	 */
	public function __call ($name, $args) {
		$controller = $this->view(trim($name, '_'));
		return call_user_func_array([$controller, 'render'], $args);
	}
}
