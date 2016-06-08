<?php
namespace Simwp\Partial;
use Simwp\Component as Component;
use Simwp\Admin;

/**
 * Provide section, menu and page rendering features
 */
class AutoRenderer extends OptionManager {
	/**
	 * Store all admin objects
	 * @var array
	 */
	protected static $_admins  = array();

	/**
	 * Store all notice objects
	 * @var array
	 */
	protected static $_notices = array();

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
	 * Get current page
	 * @return Simwp\Component\Page
	 */
	public static function currentPage(){
		return static::$_current->page;
	}

	/**
	 * Get current admin
	 * @return Simwp\Component\Admin
	 */
	public static function currentAdmin(){
		return static::$_current->admin;
	}

	/**
	 * Get current section
	 * @return string
	 */
	public static function currentSection(){
		return static::$_current->section;
	}

	/**
	 * Get current menu
	 * @return Simwp\Component\Menu
	 */
	public static function currentMenu(){
		return static::$_current->menu;
	}

	/**
	 * Check if section found
	 * @return boolean
	 */
	public static function currentFound(){
		return static::$_current->found;
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
		$name = str_replace('_', ' ', str_replace('Section_', '', $section));
		$split= explode('\\', $name);
		$name = array_pop($split);

		return ucfirst($name);
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
			$name  = $section::$name;

			if(!$name){
				$name = static::_sanitizeSectionName($section);
			}

			$section_url = static::slug($section);

			if($section === $current){
				echo '<a class="nav-tab simwp-nav-tab nav-tab-active" href="?page=' . $page->slug . '&section=' . $section_url .'" style="margin-left: 0; margin-right: 0">' . $name . '</a>';
			}
			else {
				echo '<a class="nav-tab simwp-nav-tab" href="?page=' . $page->slug . '&section=' . $section_url .'" style="margin-left: 0; margin-right: 0">' . $name . '</a>';
			}
		}

		echo '</h2>';
	}

	/**
	 * Register stylesheets
	 */
	protected static function _registerStyles($current){
		// Enqueue required script
		static::bind('admin_enqueue_scripts', function(){
			if(static::current('found')){
				wp_enqueue_style('simwp/css' , static::url( static::PATH . '/extras/simwp.min.css'));
			}

			wp_enqueue_style('simwp-notices/css', static::url( static::PATH . '/extras/simwp-notices.min.css'));
		});
	}

	/**
	 * Render a page
	 * @param  object $current
	 * @return bool
	 */
	protected static function _renderSection($current){
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
				// remove the __button call
				array_pop($fields);

				// Custom rendering with tabs
				if(!in_array('__render', $fields)){
					foreach($fields as $field){
						$des   = $admin->trans(static::_describeField($field));
						add_settings_field($field, $des, array($obj, $field), $page->slug, $section);
						register_setting($section, $field);
					}
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
	protected static function _renderPage($current){
		if($current->found){
			$admin   = $current->admin;
			$menu    = $current->menu;
			$page    = $current->page;
			$section = $current->section;

			$sectionExists = false;

			if(class_exists($section)){
				$obj = new $section;
				$sectionExists = true;
				$fields = get_class_methods($obj);
			}

			// custom render on section level
			if ($sectionExists && in_array('__render', $fields)) {
				echo static::_renderNavTabs($page, $section);
				$obj->__render();
			}
			// auto render into sections
			else if ($sectionExists && $page->render === '') {
				echo static::_renderNavTabs($page, $section);

				echo $page->before;

				settings_fields($section);
				do_action('admin_simwp_section');
				do_settings_sections($page->slug);
				$obj->__button();

				echo $page->after;
			}
			// auto redirect
			else if (strpos($page->render, 'redirect:') === 0) {
				$url = substr($page->render, 9);

				echo '<script>window.location.replace("' . $url . '")</script>';
				die();
			}
			// custom render page level
			else if($page->render) {
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
		$current = new \stdClass;
		$current->found   = false;
		// admin.php?page=1
		$current->query   = preg_replace('/^.+\/wp-admin\//', '', $_SERVER['REQUEST_URI']);
		// ?page=*
		$current->slug    = isset($_GET['page']) ? $_GET['page'] : '';
		// &section=*
		$current->section = isset($_GET['section']) ? $_GET['section']  : '';
		$current->section = str_replace('-', '\\', $current->section);
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
		$newRemoved = array();

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
