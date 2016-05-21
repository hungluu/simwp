<?php
// Install CSRF for Ajax
Simwp\Admin::bind('head', function(){
	echo '<meta name="csrf-token" content="' . wp_create_nonce('simwp-ajax-update') . '">';
});

Simwp\Admin::bind('simwp_section', function(){
	echo sprintf('<input type="hidden" value="%s" name="simwp-update">', wp_create_nonce('simwp-update'));
});

// Bind admin dashboard hooks
// Create pages
Simwp\Admin::bind('menu', function(){
	Simwp::registerPages();
});

// Init option and render requested pages
Simwp\Admin::bind('init', function(){
	Simwp::manageOptions();
	Simwp::renderPage();
});

// Init notices
Simwp\Admin::bind('notices', function(){
	Simwp::renderNotices();
});

// Enqueue required script
Simwp\Admin::bind('enqueue_scripts', function(){
	wp_enqueue_script('simwp/script' , Simwp::url( __DIR__ . '/extras/js/simwp.js'), ['jquery'], false, true);
});

// Register removed notices feature
Simwp::option('---simwp-removed-notices', 'array')->set('default', [])->updated(function($key, $data, $option){
	if(!Simwp::isCsrf()){
		$removed = Simwp::get('---simwp-removed-notices');
		$removed[] = $data;
		Simwp::set('---simwp-removed-notices', $removed);
	}
});

define('SIMWP_HOOKS_LOADED', true);
