<?php

namespace IA\Enqueue_Scripts;


if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_all_css' ) ) :
	function include_all_css() {

		include_bootstrap_css();
		include_font_awesome_css();
		include_main_css();
	}
	add_action( 'wp_enqueue_scripts', 'IA\\Enqueue_Scripts\\include_all_css' );
endif;


if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_all_js' ) ) :
	function include_all_js() {

		include_jquery_js();
		include_popper_js();
		include_bootstrap_js();
		include_main_js();
	}
	add_action( 'wp_enqueue_scripts', 'IA\\Enqueue_Scripts\\include_all_js' );
endif;



// ===============
// Styles
// ===============

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_bootstrap_css' ) ) :

	function include_bootstrap_css() {

		$bootstrap_url = get_template_directory_uri() . '/assets/css/bootstrap.min.css';
		wp_enqueue_style( 'bootstrap', $bootstrap_url, array(), '4.3.0', 'all' );
	}
endif;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_font_awesome_css' ) ) :

	function include_font_awesome_css() {

		$font_awesome_url = 'https://use.fontawesome.com/releases/v5.8.2/css/all.css';
		wp_enqueue_style( 'font_awesome', $font_awesome_url, array(), '1.0.0', 'all' );
	}
endif;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_main_css' ) ) :

	function include_main_css() {

		wp_enqueue_style( 'main_css', get_stylesheet_uri(), array(), '1.0.0', 'all' );
	}
endif;



// ===============
// Scripts
// ===============

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_jquery' ) ) :

	function include_jquery_js() {
		wp_enqueue_script( 'jquery' );
	}
endif;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_popper_js' ) ) :

	function include_popper_js() {
		$popper_url = 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js';
		wp_enqueue_script( 'popper', $popper_url, array(), '1.14.7', true );
	}
endif;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_bootstrap_js' ) ) :

	function include_bootstrap_js() {
		$bootstrap_url = 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js';
		wp_enqueue_script( 'bootstrap', $bootstrap_url, array(), '4.3.1', true );
	}
endif;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_main_js' ) ) :

	function include_main_js() {
		$main_js_url = get_template_directory_uri() . '/assets/js/script.js';
		wp_enqueue_script( 'main_js', $main_js_url, array(), '1.0.0', true );
	}
endif;
