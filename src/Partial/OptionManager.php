<?php
namespace Simwp\Partial;
use Simwp\Component as Component;

/**
 * Provide options managing features
 */
abstract class OptionManager extends UserManager{
	protected static $_options = [];

	/**
	 * Register an un-managed option
	 * @param  string $key
	 * @return Simwp\Component\Option
	 */
	public static function option($key){
		if(!key_exists($key, static::$_options)){
			static::$_options[$key] = new Component\Option($key);
		}

		return static::$_options[$key];
	}

	/**
	 * Get an option's value
	 * @param  string   $key
	 * @param  optional mixed $def a default value to return if option not found
	 * @return mixed
	 */
	public static function get($key, $def = null){
		if($def === null){
			$def = static::option($key)->default;
		}

		#HR 5/17/16 option serialization is handled by wp
		return get_option($key, $def);
	}

	/**
	 * Set an option's value
	 * @param  string $key
	 * @param  mixed  $value
	 * @return mixed  option value that was set
	 */
	public static function set($key, $value){
		#HR 5/17/16 option serialization is handled by wp
		update_option($key, $value);
	}

	/**
	 * Determine if an option is sent from user
	 * Optional callback
	 * @param  string   $key
	 * @param  optional function $fn callback
	 * @return boolean  true if option is sent and vice versa
	 */
	public static function updated($key, $fn = false){
		// if option is not registered
		if(!key_exists($key, static::$_options)){
			return false;
		}

		$current = static::$_current;
		$option  = static::$_options[$key];

		if(static::cant($option->level)){
			return false;
		}

		$data = static::sanitizeOption($key, $option->type);

		if($data !== false && $fn !== false){
			// $key, $value, $option
			return call_user_func($fn, $key, $data, $option);
		}

		return $data;
	}

	/**
	 * Attermpt to sanitize _POST data for options
	 * @param  string $key _POST and option key
	 * @param  string $type
	 * @return mixed       return false on failure
	 */
	public static function sanitizeOption($key, $type){
		if(isset($_POST[$key])){
			return $_POST[$key];
		}
		else if($type === 'boolean'){
			return 'off';
		}
		else if($type === 'array'){
			return [];
		}

		return false;
	}

	/**
	 * Get option key
	 * @param  string $key
	 * @return string 	   final key
	 */
	public static function key($key){
		return $key;
	}
}
