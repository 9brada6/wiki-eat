<?php

declare( strict_types = 1 );

namespace WE\Aliment;

function change_pos_auth($post_id){
    if ( ! wp_is_post_revision( $post_id ) ){

        // unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', '\\WE\\Aliment\\change_pos_auth' );

		$user = get_user_by( 'login', 'administrator' );
		$user2 = get_user_by( 'login', 'brada' );

        if ( $user instanceof \WP_User ) {
             $args = array(
				'ID' => $post_id,
				'post_author' => $user->ID
			);
            wp_update_post( $args );
        } elseif( $user2 instanceof \WP_User ) {
			$args = array(
				'ID' => $post_id,
				'post_author' => $user2->ID
			);
            wp_update_post( $args );
		}

       // re-hook this function
       add_action( 'save_post', '\\WE\\Aliment\\change_pos_auth' );

    }
}
add_action( 'save_post', '\\WE\\Aliment\\change_pos_auth' );


function ev_unregister_taxonomy(){
    register_taxonomy('post_tag', array());
}
add_action('init', '\\WE\\Aliment\\ev_unregister_taxonomy');

function remove_menus(){
    remove_menu_page('edit-tags.php?taxonomy=post_tag'); // Post tags
}

add_action( 'admin_menu', '\\WE\\Aliment\\remove_menus' );



function auto_update_post_thumbnail( $post_id ){
	if ( get_post_thumbnail_id( $post_id ) || get_post_type( $post_id ) !== 'post' ) {
		return;
	}

	$aliment_images = get_aliment_images( $post_id );
	$aliment_images = explode( ';', $aliment_images );

	$thumbnail_to_set = 0;
	if ( isset( $aliment_images[0] ) && is_numeric( $aliment_images[0] ) ) {
		$thumbnail_to_set = $aliment_images[0];
	}


	if ( $thumbnail_to_set ) {
		set_post_thumbnail( $post_id, $thumbnail_to_set );
	}

}
add_action( 'save_post', '\\WE\\Aliment\\auto_update_post_thumbnail', 1, 100 );
