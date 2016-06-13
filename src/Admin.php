<?php
namespace Simwp;
use Simwp;

class Admin extends Component\Base {
	const PATH = __DIR__;

	// using wpcore translating feature
	public $translate = true;
	// text domain
	public $transName = 'default';
	// extra classNames
	public $className = '';

	/**
	 * Automatically get translated strings if flag 'translate' is true
	 * @param  string $value
	 * @return string
	 */
	public function trans($value){
		if($this->translate){
			return __($value, $this->transName);
		}
		else {
			return __($value, 'default');
		}
	}

	/**
	 * Create new menu item
	 * @param  string $name name of menu item
	 * @return Simwp\Item\Menu
	 */
	public function menu($name){
		$menu = $this->items[] = new Component\Menu($name);
		return $menu;
	}

	/**
	 * Register all menus
	 * @param  object $current current request tracker from Simwp gate-way
	 */
	public function register($current){
		foreach ($this->items as $i_menu => $menu) {
			$pages = $menu->items;
			$page_count = count($pages);
			$rendered_pages = array();
			$rendered_menu  = array();
			$menu_slug = null;

			if(count($pages) > 0){
				// Rewrite menu registering part to work with multi-access-level pages

				foreach ($pages as $i_page => $page) {
					$render = 'Simwp::renderPage';

					if($page->level === ''){
						$page->level = $menu->level;
					}

					if(Simwp::cant($page->level)){
						continue;
					}

					// ==
					$page->slug = $this->name . '-' . $page->slug;

					if($menu_slug === null){
						$menu_slug = $page->slug;
					}

					$name  = ucfirst($this->trans($page->name));
					// Add submenu ( page )
					$rendered_pages[] = array(
						$menu_slug,
						$name,
						$name,
						$page->level,
						$page->slug,
						$render
					);

					// update tracker if current page is requested and will be rendered
					if($page->slug === $current->slug){
						if(!$current->section){
							$current->section = count($page->items) > 0 ? $page->items[0] : '';
						}

						$current->found   = true;
						$current->menu    = $menu;
						$current->page    = $page;
						$current->admin   = $this;
					}
				}

				// use first page as front page
				$menu_name = ucfirst($this->trans($menu->name));
				$icon = Simwp::icon($menu->icon);

				// main menu
				$rendered_menu = array(
					$menu_name,
					$menu_name,
					$menu->level,
					$menu_slug,
					null,
					$icon,
					$menu->order
				);

				call_user_func_array('add_menu_page', $rendered_menu);

				foreach ($rendered_pages as $submenu) {
					call_user_func_array('add_submenu_page', $submenu);
				}
			}
		}
	}


	/**
	 * Register an admin hook
	 */
	public static function bind($hook, $fn){
		add_action('admin_' . $hook, $fn);
	}
}
