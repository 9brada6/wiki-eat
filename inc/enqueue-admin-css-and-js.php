<?php

namespace IA\Enqueue_Scripts;

if ( ! function_exists( 'IA\\Enqueue_Scripts\\include_all_admin_css' ) ) :
	function include_all_admin_css() {
		enqueue_admin_css();

		if ( is_admin() ) :
			wp_enqueue_media();
		endif;

		wp_enqueue_script( 'main_js', get_template_directory_uri() . '/assets/js-backend/script.js', ['jquery'], '1.0.0', true );

	}
	add_action( 'admin_enqueue_scripts', 'IA\\Enqueue_Scripts\\include_all_admin_css', 100 );
endif;


function enqueue_admin_css() {
	$admin_css = get_theme_file_uri( '/assets/css-backend/style.css' );
	wp_enqueue_style( 'theme_backend', $admin_css, [], '1.0.0', 'all' );
}
