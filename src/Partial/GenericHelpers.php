<?php
namespace Simwp\Partial;

abstract class GenericHelpers {

	/**
	 * Convert level string to a wordpress capability
	 * @param  string $level
	 * @return string
	 */
	public static function level($level){
		switch ($level) {
			case 'network':
				return 'manage_network';
			case 'editor':
				return 'delete_pages';
			case 'author':
				return 'publish_posts';
			case 'contributor':
				return 'edit_posts';
			case 'subscriber':
				return 'read';
			// case 'admin':
			default:
				return 'activate_plugins';
		}
	}

	/**
	 * Normalize name to slug ultility
	 * @param  string $name
	 * @return string
	 */
	public static function slug($name){
		return strtolower(trim(preg_replace('/[^\w]/', '-', $name), '-'));
	}

	/**
	 * Get icon
	 * @param  string $icon icon name
	 * @return string       filtered icon name
	 */
	public static function icon($icon){
		return 'dashicons-' . $icon;
	}

	/**
	 * Bind a hook
	 * @param  string   $hook hook name
	 * @param  function $fn   callback
	 */
	public static function bind($hook, $fn){
		add_action($hook, $fn);
	}

	/**
	 * Get public url based on dir path
	 * @param  optional string $abs_path absolute path,
	 *                         default is null, will become shortcut of wp get_home_url
	 * @return			string
	 */
	public static function url($abs_path = null){
		if($abs_path !== null){
			$base_path = str_replace('\\', '/', ABSPATH);
			return str_replace($base_path, get_home_url() . '/', str_replace('\\', '/', $abs_path));
		}
		else{
			return get_home_url();
		}
	}

	/**
	 * Create and return a component
	 * @param  string component class
	 * @return Simwp\component\Base
	 */
	public static function make($name){
		$class = 'Simwp\\Component\\' + ucfirst($name);

		return new $class('Component');
	}

	/**
	 * Auto-binding for is- prefixed methods
	 * @return boolean
	 */
	 public static function is($sth){
		 $args = func_get_args();
		 $method = 'is' . ucfirst($args[0]);
		 if(count($args) === 1){
			 return static::$method();
		 }
		 else{
			 $args = array_slice($args, 1);
			 return call_user_func_array('static::' . $method, $args);
		 }
	 }
}
