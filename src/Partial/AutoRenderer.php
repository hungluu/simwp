<?php
namespace Simwp\Partial;
use Simwp\Component as Component;
use Simwp\Admin;

/**
 * Provide section, menu and page rendering features
 */
abstract class AutoRenderer extends OptionManager {
	/**
	 * Store all admin objects
	 * @var array
	 */
	protected static $_admins  = [];

	/**
	 * Store all notice objects
	 * @var array
	 */
	protected static $_notices = [];

	/**
	 * Store current rendering information
	 * - query : current request_uri
	 * - slug  : current slug
	 * --------------------------------------------
	 * - found(boolean) : if the following information found
	 * - admin   : current admin object
	 * - page    : current page object
	 * - section : current section object
	 * @var array
	 */
	protected static $_current= null;

	/**
	 * Retrieve a translated text using wordpress's core l10n api
	 * @param  string $text    	  text to be translated
	 * @param  mixed  $textdomain text domain to be used,
	 *                            !if true is provided, auto find current admin
	 *                            handler to translate
	 * @return string
	 */
	public static function trans($text, $textdomain = true){
		if($textdomain === true && static::current('found')){
			return static::current('admin')->trans($text);
		}

		return __($text, $textdomain);
	}

	/**
	 * Create a new admin component
	 * @param  string      $name
	 * @return Simwp\Admin
	 */
	public static function admin($name){
		$admin = static::$_admins[] = new Admin($name);
		return $admin;
	}

	/**
	 * Create a new notice component
	 * @param  string $name required name to detect option
	 * @return Simwp\Component\Notice
	 */
	public static function notice($name){
		$notice = static::$_notices[] = new Component\Notice($name);
		return $notice;
	}

	/**
	 * Create an alert ( a preset of notice )
	 * @param  			string $name
	 * @param  optional string $type
	 */
	public static function alert($name, $type = 'primary'){
		return static::notice('---simwp-option-violated')->set('force', true)->set('type', $type);
	}

	/**
	 * Get current rendering information
	 * @param  optional string $key
	 */
	public static function current($key = false){
		if($key){
			return static::$_current->$key;
		}

		return static::$_current;
	}

	/***************
	 * BASE EVENTS *
	 **************/

	/**
	 * @event render
	 */
	public static function renderSection(){
		return static::_renderSection(static::$_current);
	}

	/**
	 * @event register pages ( menus )
	 */
	public static function registerPages(){
		static::$_current = static::_registerPages(static::$_admins);
		// register simwp styles only when registered section found
		// or a page that's handled by Simwp's auto-rendering feature
		static::_registerStyles(static::$_current);
	}

	/**
	 * @event render notices
	 */
	public static function renderNotices(){
		return static::_renderNotices(static::$_notices);
	}

	/**
	 * @event render pages
	 */
	public static function renderPage(){
		return static::_renderPage(static::$_current);
	}

	//====================================
	// RENDERING HELPERS
	//====================================

	/**
	 * Get describe text of fields in form ( Section )
	 * @param  string $methodName
	 * @return string
	 */
	protected static function _describeField($methodName){
		return ucfirst(str_replace('_', ' ', $methodName));
	}

	/**
	 * Sanitize the section name ( usually as Section_Example )
	 * @param  string $section name that need to be sanitized
	 * @return string
	 */
	protected static function _sanitizeSectionName($section){
		return str_replace('_', ' ', ucfirst(str_replace('Section_', '', $section)));
	}

	/**
	 * Render navbar tabs for a page
	 * @param  Simwp\Component\Page $page
	 * @param  string				$current string describe current section
	 */
	protected static function _renderNavTabs($page, $current){
		echo '<div id="icon-themes" class="icon32"><br></div><h2 class="nav-tab-wrapper">';

		foreach ($page->items as $section) {
			$class = 'nav-tab simwp-nav-tab';
			$name  = static::_sanitizeSectionName($section);

			if($section === $current){
				echo '<a class="nav-tab simwp-nav-tab nav-tab-active" href="#" style="margin-left: 0; margin-right: 0">' . $name . '</a>';
			}
			else {
				echo '<a class="nav-tab simwp-nav-tab" href="?page=' . $page->slug . '&section=' . $section .'" style="margin-left: 0; margin-right: 0">' . $name . '</a>';
			}
		}

		echo '</h2>';
	}

	/**
	 * Register stylesheets
	 */
	protected static function _registerStyles($current){
		if($current->found){
			// Enqueue required script
			static::bind('admin_enqueue_scripts', function(){
				wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
				wp_enqueue_style('simwp/css' , static::url( static::PATH . '/extras/simwp.min.css'));
			});
		}
	}

	/**
	 * Render a page
	 * @param  object $current
	 * @return bool
	 */
	protected static function _renderPage($current){
		if($current->found){
			$admin   = $current->admin;
			$menu    = $current->menu;
			$page    = $current->page;
			$section = $current->section;

			add_settings_section($section, "", null, $page->slug);

			if(class_exists($section)){
				$obj    = new $section();
				$fields = get_class_methods($section);
				// remove the __call magic method
				array_pop($fields);

				foreach($fields as $field){
					$des   = $admin->trans(static::_describeField($field));
					add_settings_field($field, $des, array($obj, $field), $page->slug, $section);
					register_setting($section, $field);
				}
			}
		}

		return $current->found;
	}

	/**
	 * Render a section
	 * @param  object $current
	 * @return boolean
	 */
	protected static function _renderSection($current){
		if($current->found){
			$admin   = $current->admin;
			$menu    = $current->menu;
			$page    = $current->page;
			$section = $current->section;
			$material= $admin->material;

			// auto render into sections
			if ($page->render === '') {
				echo static::_renderNavTabs($page, $section);

				echo $page->before;

				settings_fields($section);
				do_action('admin_simwp_section');
				do_settings_sections($page->slug);
				submit_button();

				echo $page->after;
			}
			// auto redirect
			else if (strpos($page->render, 'redirect:') === 0) {
				$url = substr($page->render, 9);

				echo '<script>window.location.replace("' . $url . '")</script>';
				die();
			}
			// use render function
			else {
				call_user_func($page->render);
			}
		}

		static::_afterContents();
		return $current->found;
	}

	/**
	 * Register pages, find current page
	 * @param  array $admins
	 * @return object
	 */
	protected static function _registerPages($admins){
		$current = new \stdClass();
		$current->found   = false;
		// admin.php?page=1
		$current->query   = preg_replace('/^.+\/wp-admin\//', '', $_SERVER['REQUEST_URI']);
		// ?page=*
		$current->slug    = isset($_GET['page']) ? $_GET['page'] : '';
		// &section=*
		$current->section = isset($_GET['section']) ? $_GET['section']  : '';
		$current->admin   = null;
		$current->page    = null;
		$current->menu    = null;

		foreach ($admins as $admin) {
			$admin->register($current);
		}

		return $current;
	}

	/**
	 * Render notics
	 * @param  array $notices [description]
	 */
	protected static function _renderNotices($notices){
		static::_beforeContents();
		$removed = static::get('---simwp-removed-notices');
		$newRemoved = [];

		foreach ($notices as $notice) {
			if(!in_array($notice->name, $removed) || $notice->force === true){
				echo sprintf('<div id="simwp-notice-%s" class="simwp-notice-%s notice updated simwp-notice %s %s">',
					$notice->name,
					$notice->type,
					$notice->dismissible ? 'is-dismissible' : '',
					$notice->removable ? 'is-removable' : '');

				foreach ($notice->items as $line) {
					echo sprintf('<p>%s</p>', static::trans($line));
				}

  				echo '</div>';
			}
			else {
				$newRemoved[] = $notice->name;
			}
		}

		static::set('---simwp-removed-notices', $newRemoved);
	}

	/**
	 * Start simwp-ui div
	 */
	protected static function _beforeContents(){
		if(static::current('found')){
			echo sprintf('<div class="simwp-ui %s">', static::current('admin')->className);
		}
	}

	/**
	 * Close simwp-ui div
	 */
	protected static function _afterContents(){
		if(static::current('found')){
			echo '</div>';
		}
	}
}
