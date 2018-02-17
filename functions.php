<?php
define( 'VERSION', '0.0.1' );

/**
 * Load Scripts and Styles
 */
add_action( 'wp_enqueue_scripts', 'loadFrontScriptsStyles' );
function loadFrontScriptsStyles() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('loogmanBootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), VERSION, true);
	wp_enqueue_script('loogmanMain', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'loogmanBootstrap'), VERSION, true);

	wp_enqueue_style('loogmanBootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', '', VERSION, '');
	wp_enqueue_style('loogmanBootstrapTheme', get_stylesheet_directory_uri() . '/assets/css/bootstrap-theme.min.css', '', VERSION, '');
	wp_enqueue_style('loogmanMain', get_stylesheet_directory_uri() . '/assets/css/main.css', array('loogmanBootstrap'), VERSION, '');
}


/**
 * Register Thumbnails
 */
if (function_exists( 'add_theme_support' )) {
	add_theme_support( 'post-thumbnails' );
	add_image_size('thumb-small', 100, 100, true);
	add_image_size('thumb-medium', 280, 160, true);
}


/**
 * Register Menus
 */
add_action( 'init', 'registerMenus' );
function registerMenus() {
	register_nav_menus( array(
		'main-menu' => 'Main Menu'
	));
}

/**
 * Include Bootstrap NavWalker
 */
require_once('libs/wp_bootstrap_navwalker/wp_bootstrap_navwalker.php');