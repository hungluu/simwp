<?php
namespace Simwp\Partial;

/**
 * Provide more functionality on views
 */
abstract class Views extends OptionAutoHandler {
	public function isDashboard(){
		return is_admin();
	}
}
