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
	public $className = 'simwp-material-ui';

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

			if($page_count > 0){
				// use first page as front page
				$menu->slug = $menu->items[0]->slug;
				$name = ucfirst($this->trans($menu->name));
				$level= $menu->level;
				$icon = Simwp::icon($menu->icon);

				// ===
				$menu->slug = $this->name . '-' . $menu->slug;

				// Add main menu
				add_menu_page(
					$name,
					$name,
					$level,
					$menu->slug,
					null,
					$icon,
					$menu->order
				);

				foreach ($pages as $i_page => $page) {
					$render = 'Simwp::renderPage';

					if($page->level === ''){
						$page->level = $menu->level;
					}

					$name  = ucfirst($this->trans($page->name));
					$level = $page->level;

					// ==
					$page->slug = $this->name . '-' . $page->slug;

					// Add submenu ( page )
					add_submenu_page(
						$menu->slug,
						$name,
						$name,
						$level,
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
