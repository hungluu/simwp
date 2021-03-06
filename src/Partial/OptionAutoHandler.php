<?php
namespace Simwp\Partial;
use Symfony\Component\Validator\Validation;

abstract class OptionAutoHandler extends AutoRenderer {
	/**
	 * Managed options, auto-processed on load
	 * @var array
	 */
	protected static $_managed_options = array();

	/************************************
	 * OPTIONS ADDITIONAL FUNCTIONALITY *
	 ***********************************/

	/**
	 * Register an managed option
	 * @param  string $key
	 * @return Simwp\Component\Option
	 */
	public static function watch($key){
		if(!in_array($key, static::$_managed_options)){
			static::$_managed_options[] = $key;
		}
		return static::option($key);
	}

	/**
	 * Detect options updated and managed
	 * @param  			string   $key
	 * @param  optional callable $fn    optional callback on success
	 * ================================================================
	 * These arguments should only be used internally
	 * @param  optional array $errorCollector error collection
	 * @param  optional Symfony\Component\Validator\Validation $validator validator
	 * @return boolean
	 */
	public static function handled($key, $fn = null, &$errorCollector = array(), &$validator = null){
		// NOTE : Crsf or extra security checks should be used outside

		// check option existence and if updated
		$data = static::updated($key);
		if($data === false){
			return false;
		}

		$option = static::$_options[$key];

		// if option is handled to only some schemes
		if(!static::isAccessible($key)){
			return false;
		}

		// check with validations
		$errors = static::validateOption($data, $key, $validator);
		if(count($errors) > 0){
			$errorCollector[$key] = $errors;

			return false;
		}

		// OPTION MANAGED

		if($fn){
			return call_user_func($fn, $key, $data, $option);
		}

		// return sanitized value
		return $data;
	}

	/**
	 * Check if input data is acceptable for an option
	 * @param  mixed  $data
	 * @param  string $key
	 * @param  object $validator a validator ( with ::validate )
	 * @return array empty array on success
	 */
	public static function validateOption($data, $key, $validator){
		$errors = array();
		$option = static::option($key);

		if(!$option->isValidated()){
			return $errors;
		}

		foreach ($option->validators as $rule) {
			$e = $validator->validate($data, $rule);

			if(count($e) > 0){
				$errors[] = $e;
			}
		}

		return $errors;
	}

	/**
	 * Check if option is accessible in current page
	 * @param  string  $key
	 * @return boolean
	 */
	public static function isAccessible($key){
		$current = static::$_current;
		$option  = static::option($key);

		if($option->isLimited()
			&& !in_array($current->section, $option->items)
			&& !in_array($current->page, $option->items)
			&& !in_array($current->menu, $option->items)
			&& !in_array($current->admin, $option->items)
			&& !in_array($current->query, $option->items)
		){
			return false;
		}

		return true;
	}

	/**
	 * @event manage options
	 */
	public static function manageOptions(){
		if(empty($_POST) || static::isCsrf()){
			return false;
		}

		if(is_admin()){
			$validator = Validation::createValidatorBuilder()->getValidator();
			// temporarily save option values to used after no violation found
			$options   = array();
			$errors    = array();
			$isForm    = isset($_POST['submit']);
			$current   = static::$_current;
			$textdomain= static::current('found') && static::current('admin') ? static::current('admin')->transName : 'default';
			$violated  = false;

			// get all updated options and validate
			foreach (static::$_managed_options as $key) {
				$option = static::$_options[$key];
				$notAccessible = false;
				$data = static::handled($key, null, $errors, $validator);

				if($data !== false){
					$options[$key] = $data;
				}
			}

			// init all options once
			if(count($options) > 0){
				if(count($errors) === 0){
					foreach($options as $key => $data){
						static::set($key, $data);
					}

					$notice = static::notice('---simwp-option-violated')->alert()->append(array(static::trans('Settings saved.', $textdomain)));
				}
				else {
					$notice = static::notice('---simwp-option-violated')->alert();

					$messages = array();
					$messages[] = static::trans('Settings not saved. Please fix the following errors', $textdomain);

					// show error messages
					foreach($errors as $key => $errorList){
						if(count($errorList) > 0){
							$messages[] = '<b>' . $key . ' :</b>';
							foreach($errorList as $error){
								foreach($error as $e){
									$messages[] = static::trans(' - ' . $e->getMessage(), $textdomain);
								}
							}
						}
					}

					$notice->append($messages);
				}
			}
		}
	}

	/**
	 * Check if current request represent a cross-site forgery attack
	 * @return bool
	 */
	public static function isCsrf(){
		$token = isset($_POST['simwp-update']) ? $_POST['simwp-update'] : false;

		if(!$token){
			$token = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : false;

			if(!$token){
				return true;
			}

			return !static::nonce('simwp-ajax-update', $token);
		}
		else{
			return !static::nonce('simwp-update', $token);
		}
	}
}
