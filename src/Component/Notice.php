<?php
namespace Simwp\Component;

class Notice extends Base{
	public $type        = 'primary';
	public $dismissible = true;
	public $removable   = false;
	public $force       = false;

	/**
	 * Apply a preset named alert to show alert messages
	 */
	public function alert(){
		$this->force = true;
		$this->type  = 'danger';

		return $this;
	}
}
