<?php
namespace Simwp;
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
	 * @param  string $abs_path absolute path
	 * @return string
	 */
	public static function url($abs_path){
		// minor fix
		$home_path = str_replace('\\', '/', ABSPATH);
		return str_replace($home_path, get_site_url() . '/', str_replace('\\', '/', $abs_path));
	}
}
