<?php
namespace Simwp\Partial;
/**
 * Provide simple user managing functionality
 */
abstract class UserManager extends GenericHelpers{
	/**
	 * Detect current user capability
	 * @param  string  $capability
	 * @return boolean
	 */
	public static function can($capability){
		return current_user_can($capability);
	}

	/**
	 * Detect if current user doesn't have a capability
	 * @param  string  $capability
	 * @return boolean
	 */
	public static function cant($capability){
		return !static::can($capability);
	}

	/**
	 * Detect if current user is admin
	 * @return boolean
	 */
	public static function isAdmin(){
		return static::can('activate_plugins');
	}

	/**
	 * Detect if current user is network admin
	 * @return boolean
	 */
	public static function isNetworkAdmin(){
		return static::can('manage_network');
	}

	/**
	 * Detect if current visitor logged in as an user
	 * @return boolean
	 */
	public static function isUser(){
		return is_user_logged_in();
	}
}
