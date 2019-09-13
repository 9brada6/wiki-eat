<?php
/**
 * Define, add or remove post types.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace IA\Post_Types;

/**
 * Register the Ingredient post_type.
 *
 * @return void
 */
function create_ingredient_post_type() {

	$ingredient_args = ingredients_post_type_settings();
	register_post_type( 'ingredient', $ingredient_args );
}
add_action( 'init', 'IA\\Post_Types\\create_ingredient_post_type' );

/**
 * Register the Company post_type.
 *
 * @return void
 */
function create_company_post_type() {

	$company_args = company_post_type_settings();
	register_post_type( 'company', $company_args );
}
//add_action( 'init', 'IA\\Post_Types\\create_company_post_type' );



/**
 * Return an array with the settings for constructing
 * the Ingredient Post Type.
 *
 * @return array The array with the settings.
 */
function ingredients_post_type_settings(): array {

	return array(
		'labels'              => get_ingredient_labels(),
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-carrot',
		'supports'            => array( 'thumbnail', 'title', 'editor', 'revisions' ),
		'has_archive'         => false,
		'can_export'          => true,
		'delete_with_user'    => false,
		'rewrite'             => array( 'slug' => 'ingrediente' ),
		'taxonomies'          => array( 'ingredients_categories' ),
	);
}

/**
 * Return an array with the settings for constructing
 * the Company Post Type.
 *
 * @return array The array with the settings.
 */
function company_post_type_settings(): array {

	return array(
		'labels'              => get_company_labels(),
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-building',
		'supports'            => array( 'title', 'editor', 'comments', 'revisions' ),
		'has_archive'         => false,
		'can_export'          => true,
		'delete_with_user'    => false,
	);
}




/**
 * Return the labels for the Ingredient Post Type.
 *
 * @return array
 */
function get_ingredient_labels(): array {

	return array(
		'name'                  => _x( 'Ingredients', 'post type', 'wiki-eat' ),
		'singular_name'         => _x( 'Ingredient', 'post type', 'wiki-eat' ),
		'add_new'               => _x( 'Add New', 'post type', 'wiki-eat' ),
		'add_new_item'          => _x( 'Add New Ingredient', 'post type', 'wiki-eat' ),
		'edit_item'             => _x( 'Edit Ingredient', 'post type', 'wiki-eat' ),
		'new_item'              => _x( 'New Ingredient', 'post type', 'wiki-eat' ),
		'view_item'             => _x( 'View Ingredient', 'post type', 'wiki-eat' ),
		'view_items'            => _x( 'View Ingredients', 'post type', 'wiki-eat' ),
		'search_items'          => _x( 'Search Ingredients', 'post type', 'wiki-eat' ),
		'not_found'             => _x( 'No Ingredients found', 'post type', 'wiki-eat' ),
		'not_found_in_trash'    => _x( 'No Ingredients found in Trash', 'post type', 'wiki-eat' ),
		'parent_item_colon'     => _x( 'Parent Ingredient', 'post type', 'wiki-eat' ),
		'all_items'             => _x( 'All Ingredients', 'post type', 'wiki-eat' ),
		'archives'              => _x( 'Ingredient Archives', 'post type', 'wiki-eat' ),
		'attributes'            => _x( 'Ingredient Attributes', 'post type', 'wiki-eat' ),
		'insert_into_item'      => _x( 'Insert into Ingredient', 'post type', 'wiki-eat' ),
		'uploaded_to_this_item' => _x( 'Upload to this Ingredient', 'post type', 'wiki-eat' ),
	);
}

/**
 * Return the labels for the Company Post Type.
 *
 * @return array
 */
function get_company_labels(): array {

	return array(
		'name'                  => _x( 'Companies', 'post type', 'wiki-eat' ),
		'singular_name'         => _x( 'Company', 'post type', 'wiki-eat' ),
		'add_new'               => _x( 'Add New', 'post type', 'wiki-eat' ),
		'add_new_item'          => _x( 'Add New Company', 'post type', 'wiki-eat' ),
		'edit_item'             => _x( 'Edit Company', 'post type', 'wiki-eat' ),
		'new_item'              => _x( 'New Company', 'post type', 'wiki-eat' ),
		'view_item'             => _x( 'View Company', 'post type', 'wiki-eat' ),
		'view_items'            => _x( 'View Companies', 'post type', 'wiki-eat' ),
		'search_items'          => _x( 'Search Companies', 'post type', 'wiki-eat' ),
		'not_found'             => _x( 'No Companies found', 'post type', 'wiki-eat' ),
		'not_found_in_trash'    => _x( 'No Companies found in Trash', 'post type', 'wiki-eat' ),
		'parent_item_colon'     => _x( 'Parent Company', 'post type', 'wiki-eat' ),
		'all_items'             => _x( 'All Companies', 'post type', 'wiki-eat' ),
		'archives'              => _x( 'Company Archives', 'post type', 'wiki-eat' ),
		'attributes'            => _x( 'Company Attributes', 'post type', 'wiki-eat' ),
		'insert_into_item'      => _x( 'Insert into Company', 'post type', 'wiki-eat' ),
		'uploaded_to_this_item' => _x( 'Upload to this Company', 'post type', 'wiki-eat' ),
	);
}


/**
 * @todo modify args and labels.
 */
function add_ingredients_category() {
    // create a new taxonomy
    register_taxonomy(
        'ingredients_categories',
        'ingredient',
        array(
			'label' => __( 'Ingredients Categories', 'wiki-eat' ),
			'hierarchical' => true,
        )
    );
}
add_action( 'init', '\\IA\\Post_Types\\add_ingredients_category' );
