<?php
/**
 * Create the websites roles.
 *
 * By default, without Administrator, there will be
 * 2 more roles:
 *
 * 1. Administrator -> all Capabilities.
 * 2. Contributor -> newly created accounts, they can edit and add all sort
 * of posts.
 * 3. Editor -> They can edit, delete, and will manage the site.
 */

declare( strict_types = 1 );

namespace WE\Users;

/**
 * Make sure that the roles are restored back to WordPress default.
 *
 * @return void
 */
function restore_theme_roles() {
	delete_all_roles();

	create_administrator_role();
	create_moderator_role();
	create_contributor_role();

	update_option( 'default_role', 'contributor' );
}
// add_action( 'after_switch_theme', 'WE\\Users\\restore_theme_roles' );


/**
 * Delete all Database roles registered.
 *
 * @return void
 */
function delete_all_roles() {
	$wp_roles = wp_roles();
	$current_roles = array_keys( $wp_roles->get_names() );

	foreach ( $current_roles as $role_slug ) {
		if ( ! is_int( $role_slug ) ) :
			remove_role( $role_slug );
		endif;
	}
}


function create_contributor_role() {
	add_role( 'contributor', 'Contributor' );

	$role = get_role( 'contributor' );

	if ( null === $role ) {
		return;
	}

	$role->add_cap( 'read' );


	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_published_posts' );

	$role->add_cap( 'publish_posts' );


}

function create_moderator_role() {
	add_role( 'moderator', 'Moderator' );

	$role = get_role( 'moderator' );

	if ( null === $role ) {
		return;
	}

	$role->add_cap( 'read' );
	$role->add_cap( 'delete_others_posts' );
	$role->add_cap( 'delete_posts' );
	$role->add_cap( 'delete_private_posts' );
	$role->add_cap( 'delete_published_posts' );
	// $role->add_cap( 'manage_categories' );
	$role->add_cap( 'moderate_comments' );
	$role->add_cap( 'read_private_posts' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts' );
}

function create_administrator_role() {
	add_role( 'administrator', 'Administrator' );

	$role = get_role( 'administrator' );

	if ( $role === null ) {
		return;
	}

	$role->add_cap( 'switch_themes' );
	$role->add_cap( 'edit_themes' );
	$role->add_cap( 'activate_plugins' );
	$role->add_cap( 'edit_plugins' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'edit_files' );
	$role->add_cap( 'manage_options' );
	$role->add_cap( 'moderate_comments' );
	$role->add_cap( 'manage_categories' );
	$role->add_cap( 'manage_links' );
	$role->add_cap( 'upload_files' );
	$role->add_cap( 'import' );
	$role->add_cap( 'unfiltered_html' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts' );
	$role->add_cap( 'edit_pages' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_10' );
	$role->add_cap( 'level_9' );
	$role->add_cap( 'level_8' );
	$role->add_cap( 'level_7' );
	$role->add_cap( 'level_6' );
	$role->add_cap( 'level_5' );
	$role->add_cap( 'level_4' );
	$role->add_cap( 'level_3' );
	$role->add_cap( 'level_2' );
	$role->add_cap( 'level_1' );
	$role->add_cap( 'level_0' );

	// WordPress Version 2.1.0 Defaults.
	$role->add_cap( 'edit_others_pages' );
	$role->add_cap( 'edit_published_pages' );
	$role->add_cap( 'publish_pages' );
	$role->add_cap( 'delete_pages' );
	$role->add_cap( 'delete_others_pages' );
	$role->add_cap( 'delete_published_pages' );
	$role->add_cap( 'delete_posts' );
	$role->add_cap( 'delete_others_posts' );
	$role->add_cap( 'delete_published_posts' );
	$role->add_cap( 'delete_private_posts' );
	$role->add_cap( 'edit_private_posts' );
	$role->add_cap( 'read_private_posts' );
	$role->add_cap( 'delete_private_pages' );
	$role->add_cap( 'edit_private_pages' );
	$role->add_cap( 'read_private_pages' );
	$role->add_cap( 'delete_users' );
	$role->add_cap( 'create_users' );

	// WordPress Version 2.3.0 Defaults.
	$role->add_cap( 'unfiltered_upload' );

	// WordPress Version 2.5.0 Defaults.
	$role->add_cap( 'edit_dashboard' );

	// WordPress Version 2.6.0 Defaults.
	$role->add_cap( 'update_plugins' );
	$role->add_cap( 'delete_plugins' );

	// WordPress Version 2.7.0 Defaults.
	$role->add_cap( 'install_plugins' );
	$role->add_cap( 'update_themes' );

	// WordPress Version 2.8.0 Defaults.
	$role->add_cap( 'install_themes' );

	// WordPress Version 3.0.0 Defaults.
	$role->add_cap( 'update_core' );
	$role->add_cap( 'list_users' );
	$role->add_cap( 'remove_users' );
	$role->add_cap( 'promote_users' );
	$role->add_cap( 'edit_theme_options' );
	$role->add_cap( 'delete_themes' );
	$role->add_cap( 'export' );
}
