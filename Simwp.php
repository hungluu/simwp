<?php
/**
 * Gate-way of Simwp . This a an very early-access of Simwp library
 *
 * @package Simwp
 * @version 0.0.6-alpha
 * @author  hungluu ( Hung Luu )
 * @license MIT
 * @copyright HR (c) 2016
 */
final class Simwp extends Simwp\Partial\Views{
	const PATH = __DIR__;

	// TODO : Add alias
}


if(!defined('SIMWP_HOOKS_LOADED')){
	require __DIR__ . '/install_hooks.php';
}

define('SIMWP_LOADED', true);
