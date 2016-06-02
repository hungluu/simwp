<?php
// Install CSRF protection for Ajax
Simwp\Admin::bind('head', function(){
	echo sprintf('<meta name="csrf-token" content="%s">', Simwp::nonce('simwp-ajax-update'));
});

Simwp\Admin::bind('simwp_section', function(){
	echo sprintf('<input type="hidden" value="%s" name="simwp-update">', Simwp::nonce('simwp-update'));
});

// Bind admin dashboard hooks
// Create pages
Simwp\Admin::bind('menu', function(){
	Simwp::registerPages();
});

// Init option and render requested pages
Simwp\Admin::bind('init', function(){
	// Register removed notices feature
	Simwp::option('---simwp-removed-notices', 'array')->set('default', array())->updated(function($key, $data, $option){
		if(!Simwp::isCsrf()){
			$removed = Simwp::get('---simwp-removed-notices');
			$removed[] = $data;
			Simwp::set('---simwp-removed-notices', $removed);
		}
	});

	Simwp::manageOptions();
	Simwp::renderSection();
});

// Init notices
Simwp\Admin::bind('notices', function(){
	Simwp::renderNotices();
});

// Enqueue required script
Simwp\Admin::bind('enqueue_scripts', function(){
	wp_enqueue_script('simwp/script' , Simwp::url( __DIR__ . '/extras/js/simwp.js'), array('jquery'), false, true);
});

define('SIMWP_HOOKS_LOADED', true);
