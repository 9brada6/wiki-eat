<?php

function we_register_menus(){
	register_nav_menu(
		'primary', __( 'Primary Menu', 'wiki-eat' )
	);
}

add_action( 'init', 'we_register_menus' );
