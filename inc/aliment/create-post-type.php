<?php
/**
 * Create the "aliment" post type. This post type is just
 * a mask over the default WordPress "post".
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Aliment;

/**
 * Modify the arguments of the default WordPress Post, before
 * registering.
 *
 * This function must be injected at 'register_post_type_args' filter.
 *
 * @param array  $args      The default arguments, passed to register_post_type().
 * @param string $post_type The post type name, being registered.
 *
 * @return array The modified arguments.
 */
function modify_default_post_type_to_aliment( array $args, string $post_type ): array {

	if ( 'post' === $post_type ) :
		$args['labels']        = get_labels();
		$args['menu_icon']     = 'dashicons-cart';
		$args['menu_position'] = 6;
	endif;

	return $args;
}

/**
 * Return the labels for the Aliment Post Type.
 *
 * @return array
 */
function get_labels(): array {

	return array(
		'name'                  => _x( 'Aliments', 'post type', 'wiki-eat' ),
		'singular_name'         => _x( 'Aliment', 'post type', 'wiki-eat' ),
		'add_new'               => _x( 'Add New', 'post type', 'wiki-eat' ),
		'add_new_item'          => _x( 'Add New Aliment', 'post type', 'wiki-eat' ),
		'edit_item'             => _x( 'Edit Aliment', 'post type', 'wiki-eat' ),
		'new_item'              => _x( 'New Aliment', 'post type', 'wiki-eat' ),
		'view_item'             => _x( 'View Aliment', 'post type', 'wiki-eat' ),
		'view_items'            => _x( 'View Aliments', 'post type', 'wiki-eat' ),
		'search_items'          => _x( 'Search Aliments', 'post type', 'wiki-eat' ),
		'not_found'             => _x( 'No Aliments found', 'post type', 'wiki-eat' ),
		'not_found_in_trash'    => _x( 'No Aliments found in Trash', 'post type', 'wiki-eat' ),
		'parent_item_colon'     => _x( 'Parent Aliment', 'post type', 'wiki-eat' ),
		'all_items'             => _x( 'All Aliments', 'post type', 'wiki-eat' ),
		'archives'              => _x( 'Aliment Archives', 'post type', 'wiki-eat' ),
		'attributes'            => _x( 'Aliment Attributes', 'post type', 'wiki-eat' ),
		'insert_into_item'      => _x( 'Insert into Aliment', 'post type', 'wiki-eat' ),
		'uploaded_to_this_item' => _x( 'Upload to this Aliment', 'post type', 'wiki-eat' ),
	);
}
