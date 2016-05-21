<?php
namespace Simwp\Form;
use Simwp;

class Image extends Input {
	public function __construct(){
		if(function_exists( 'wp_enqueue_media' )) {
		    wp_enqueue_media();
		} else {
		    wp_enqueue_style('thickbox');
		    wp_enqueue_script('media-upload');
		    wp_enqueue_script('thickbox');
		}
	}

	public function render($key, $width = 200, $height = 100){
		echo '<div class="simwp-input-image">';
		$opt = Simwp::get($key);
		if(!$opt){
			$opt = '//placehold.it/' . $width . 'x' . $height . '/ddd/fdfdfd';
		}
		echo '<img src="' . $opt . '" width="' . $width . '" height="' . $height . '">';
		$this->set('type', 'hidden');
		parent::render($key);
		echo '<button type="button" class="button button-default add" style="width:'. $width/2 .'px">' . Simwp::trans('Upload') . '</button>';
		echo '<button type="button" class="button button-default delete" style="width:'. $width/2 .'px">' . Simwp::trans('Remove') . '</button>';
		echo '</div>';
	}
}
