<?php
/**
 * Small utility functions, used across the theme.
 * These functions are very basic, and can be usually used
 * in any other theme.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Utility;

/**
 * Verify if the id of a post type is valid, and
 * the post exist.
 *
 * @param int $post_id The ID of the post.
 *
 * @return int Return the post ID if it's good, else return 0.
 */
function get_valid_post_id_if_exist( int $post_id ): int {

	if ( $post_id < 1 ) :
		return 0;
	endif;

	$post = get_post( $post_id );

	if ( $post ) :
		return $post_id;
	endif;

	return 0;
}

function get_current_url(): string {
	return "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
