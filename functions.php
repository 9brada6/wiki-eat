<?php
/**
 * Includes all files of the theme.
 *
 * Each file is used to do a specific job.
 *
 * @todo Add capability on post types.
 * @todo check phan on get_aliment_images() function.
 * @todo Add Carousel options, like carousel timeout.
 * @todo Exclude h1,h2 from the aliment content
 *
 * @todo Save meta on all post revisions.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

/**
 * We have the following namespaces:
 *    WE/Aliment: -all files inside inc/aliment.
 *    WE/Functions: -inc/functions/theme-functions.
 *    WE/Utility: -inc/functions/helper-functions.
 *    WE/Post: -all functions inside inc/post.
 *
 *
 *
 */

// =======================================================
// Main & Helper functions
// =======================================================

/**
 * Functions that are generic, and can be used in any theme.
 */
require_once get_theme_file_path( 'inc/functions/helper-functions.php' );

/**
 * Functions that are created and work specifically with this theme.
 */
require_once get_theme_file_path( 'inc/functions/theme-functions.php' );


/**
 * Execute theme specify hooks.
 */
require_once get_theme_file_path( 'inc/functions/theme.php' );

// =======================================================
// Aliment Post Type functions
// =======================================================

/**
 * Functions that helps with creating the post type.
 */
require_once get_theme_file_path( 'inc/aliment/create-post-type.php' );

/**
 * Create the backend section where the users will upload
 * and select images for each aliment.
 */
require_once get_theme_file_path( 'inc/aliment/backend-image-selection.php' );

/**
 * Create the backend editor where the users will write
 * the ingredients for each aliment.
 */
require_once get_theme_file_path( 'inc/aliment/backend-ingredients-editor.php' );

/**
 * Include other aliment functions.
 */
require_once get_theme_file_path( 'inc/aliment/aliment-functions.php' );



/**
 * Hook all the functions with specific actions and add filters.
 *
 * Todo: include jquery ui.
 */
require_once get_theme_file_path( 'inc/aliment/aliment.php' );



// =======================================================
// Ingredient Post Type functions
// =======================================================



// =======================================================
// Components that can be used in all post types.
// =======================================================

require_once get_theme_file_path( 'inc/post/class-bootstrap-carousel.php' );

require_once get_theme_file_path( 'inc/post/comments.php' );


// =======================================================
// Users functions
// =======================================================

/**
 * By default, we will need an Administrator.
 * An editor which has larger privilegies, as keeping comments clean... etc.
 * A Contributor, which can only be logged in by a Twitter/Facebook/Gmail
 * Account.
 * A Subscriber, which can only read.
 */

require_once get_theme_file_path( 'inc/users/roles.php' );

require_once get_theme_file_path( 'inc/users/capabilities.php' );


require_once get_theme_file_path( 'inc/users/users.php' );

require_once get_theme_file_path( 'inc/ingredients-calculator.php' );


// =======================================================
// External Code
// =======================================================

require_once get_theme_file_path( 'inc-external/require-plugins/class-tgm-plugin-activation.php' );

require_once get_theme_file_path( 'inc-external/wp-post-meta-revisions/wp-post-meta-revisions.php' );

function add_meta_keys_to_revision( $keys ) {
	$keys[] = 'we_ingredients';
	$keys[] = 'we_aliment_images';
	return $keys;
}
// add_filter( 'wp_post_revision_meta_keys', 'add_meta_keys_to_revision' );


require_once get_theme_file_path( 'inc/bootstrap-pagination.php' );


// ======================================================


// Functions used to display and create a way to link a Company to an Aliment.
require get_theme_file_path( 'inc/class-one-to-many-relationship.php' );
require get_theme_file_path( 'inc/link-between-aliment-and-company.php' );





// All CSS and JS from the admin is enqueued from this file.
require get_theme_file_path( 'inc/enqueue-admin-css-and-js.php' );


// All CSS and JS from the front-end is enqueued from this file.
require get_theme_file_path( 'inc/enqueue-css-and-js.php' );




/**
 * Create Custom Post Types, Like Aliments and Ingredients.
 */
require get_theme_file_path( 'inc/manage-post-types.php' );


// add_action('admin_menu','remove_default_post_type');

// function remove_default_post_type() {
//     remove_menu_page('edit.php');
// }





require get_theme_file_path( 'inc/navigation-menus.php' );

require get_theme_file_path( 'inc/walkers/class-wp-bootstrap-navwalker.php' );














add_action( 'tgmpa_register', 'wiki_eat_register_required_plugins' );

function wiki_eat_register_required_plugins() {
}


function my_modify_main_query( $query ) {
	if ( $query->is_main_query() ) {
		$query->query_vars['order'] = 'ASC';
		$query->query_vars['orderby'] = 'name';
	}
}
add_action( 'pre_get_posts', 'my_modify_main_query' );
