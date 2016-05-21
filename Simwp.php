<?php
/**
 * Gate-way of Simwp . This a an very early-access of Simwp library
 *
 * @package Simwp
 * @version 0.0.1-alpha
 * @author  Dumday ( Hung Luu )
 * @license MIT
 * @copyright HR (c) 2016
 */
final class Simwp extends Simwp\AutoProcessedOptionManager{
	const PATH = __DIR__;
}


if(!defined('SIMWP_HOOKS_LOADED')){
	require __DIR__ . '/install_hooks.php';
}
