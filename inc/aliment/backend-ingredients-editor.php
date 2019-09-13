<?php
/**
 * Manages the editor in the backend of the Aliment Post Type.
 * This editor will be used by the users to write the ingredients
 * for each aliment.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Aliment;

use WP_Error;

/**
 * Add the editor in which an user will write what ingredients an
 * aliment will have.
 *
 * This is added after title box.
 *
 * @param \WP_Post $post The post object.
 *
 * @return void
 */
function add_ingredient_editor( \WP_Post $post ) {

	if ( get_post_type( $post ) !== 'post' ) {
		return;
	}

	?>
		<h2 style="font-size:1.5rem">
			<?php _ex( 'Ingredients list:', 'backend', 'wiki-eat' ); ?>
		</h2>

		<p style="margin-left:10px; margin-bottom:-26px; margin-top: 0">
			<?php _ex( 'Only the links to ingredients will be valid.', 'backend', 'wiki-eat' ); ?>
		</p>
	<?php

	$content = get_ingredients_text( $post->ID );

	$settings = array(
		'media_buttons' => false,
		'wpautop'       => false,
		'textarea_name' => 'we_aliment_ingredients',
		'tinymce'       => array(
			'toolbar1' => 'link,bold,italic,underline',
			'toolbar2' => '',
			'toolbar3' => '',
			'toolbar4' => '',
		),
		'textarea_rows' => 10,
	);

	add_filter( 'tiny_mce_before_init', '\\WE\\Aliment\\tinymce_remove_menu_bar' );

	wp_editor( $content, 'we_ingredients_editor', $settings );

	remove_filter( 'tiny_mce_before_init', '\\WE\\Aliment\\tinymce_remove_menu_bar' );
}


/**
 * Remove the menu bar from the TinyMCE Editor.
 *
 * @param array $options Default options of the editor.
 *
 * @return array The new $options, where the menu bar is removed.
 */
function tinymce_remove_menu_bar( array $options ): array {
	$options['menubar'] = false;
		return $options;
}



/**
 * Echoes the ingredient text for an Aliment.
 *
 * @param \WP_Post|int|null $post The aliment to get the ingredients. Null defaults to global post.
 *
 * @return void
 */
function the_ingredients_text( $post = null ) { // phpcs:ignore NeutronStandard
	echo get_ingredients_text( $post ); // phpcs:ignore WordPress.Security -- Already escaped.
}

/**
 * Get the ingredient text for an Aliment.
 *
 * @param \WP_Post|int|null $post The aliment to get the ingredients. Null defaults to global post.
 *
 * @return string The ingredients of the aliments.
 */
function get_ingredients_text( $post = null ): string { // phpcs:ignore -- $post must be of mixed type

	$post = get_post( $post );

	if ( ( $post instanceof WP_Error ) ) :
		return '';
	endif;

	$post_id = $post->ID;

	$ingredients_text = get_post_meta( $post_id, 'we_ingredients', true );

	$ingredients_text = filter_ingredients_text( $ingredients_text );

	return $ingredients_text;
}


/**
 * Filters the ingredient text for non-valid HTML tags and XSS.
 *
 * @param string $ingredients_text The text to be filtered.
 *
 * @return string The filtered text.
 */
function filter_ingredients_text( string $ingredients_text ): string {

	$kses_options = get_allowed_html_in_ingredients();

	$ingredients_text = wp_kses( $ingredients_text, $kses_options );

	return $ingredients_text;
}

/**
 * Strip all the "href" attributes from "a" tags, that are not
 * pointing to the website's own ingredients.
 *
 * @param string $ingredient_text The initial text with ingredients.
 *
 * @return string The text without hyperlinks that point elsewhere.
 */
function strip_unwanted_hyperlinks( string $ingredient_text ): string {

	$matches = null;
	preg_match_all( '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $ingredient_text, $matches );

	$url_array = array();

	if ( ! empty( $matches['href'] ) ) :
		$url_array = $matches['href'];
	endif;

	$post_type_allowed = 'ingredient';

	$url_array_count = count( $url_array );
	for ( $i = 0; $i < $url_array_count; $i++ ) :

		$post_id = url_to_postid( $url_array[ $i ] );

		$post_type = false;
		if ( $post_id ) :
			$post_type = get_post_type( $post_id );
		endif;

		if ( $post_type === $post_type_allowed ) :
			continue;
		endif;

		$ingredient_text = str_replace( $url_array[ $i ], '#', $ingredient_text );

	endfor;

	return $ingredient_text;
}

/**
 * Return an array with the allowed HTML that can be used with kses.
 *
 * @return array The allowed HTML.
 */
function get_allowed_html_in_ingredients(): array {

	$kses_options = array(
		'strong' => array(),
		'em'     => array(),
		'del'    => array(
			'datetime' => array(),
		),
		'a'      => array(
			'href'  => array(),
			'title' => array(),
		),
	);

	return $kses_options;
}



/**
 * Save the ingredients text for an aliment.
 *
 * Triggers when post is saved.
 *
 * @param int $post_id The post ID that is saved.
 *
 * @return void
 */
function save_ingredients_text( int $post_id ) {

	// phpcs:ignore WordPress.Security -- No need to verify nonces.
	if ( ! isset( $_POST['we_aliment_ingredients'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security -- No need to verify nonces.
	$ingredients_text = filter_ingredients_text( $_POST['we_aliment_ingredients'] );
	$ingredients_text = strip_unwanted_hyperlinks( $ingredients_text );

	update_post_meta( $post_id, 'we_ingredients', $ingredients_text );

	// Also save a metadata for the revision.
	$parent_id = wp_is_post_revision( $post_id );
	if ( $parent_id ) {
		add_metadata( 'post', $post_id, 'we_ingredients', $ingredients_text );
	}
}


/**
 * Save the ingredients text when a revision is restored back.
 *
 * When switching to a revision, the "save_post" action is not
 * called as usual when is called by "update" button. When switching
 * to a revision, a new revision of the old post is created, so we
 * need to save again  for that too.
 *
 * @param int $post_id The post id to the revision being switched to.
 *
 * @return void
 */
function save_ingredients_text_on_revision_restore( int $post_id ) {
	$parent_id = wp_is_post_revision( $post_id );
	if ( ! $parent_id ) {
		return;
	}

	// phpcs:ignore WordPress.Security -- No need for nonce verification.
	if ( ! isset( $_REQUEST['revision'], $_REQUEST['action'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security -- No need for nonce verification.
	if ( 'restore' !== $_REQUEST['action'] ) {
		return;
	}

	// phpcs:ignore WordPress.Security -- No need for nonce verification.
	$revision_id = (int) $_REQUEST['revision'];
	$ingredients = get_metadata( 'post', $revision_id, 'we_ingredients', true );

	add_metadata( 'post', $post_id, 'we_ingredients', $ingredients );
}


/**
 * When a revision is restored, also restore the ingredients meta data.
 *
 * @param int $post_id     The newly post id.
 * @param int $revision_id The revision post id.
 *
 * @return void
 */
function restore_ingredients_on_revision( int $post_id, int $revision_id ) {
	$ingredients = get_metadata( 'post', $revision_id, 'we_ingredients', true );

	if ( false !== $ingredients ) {
		update_post_meta( $post_id, 'we_ingredients', $ingredients );
	} else {
		delete_post_meta( $post_id, 'we_ingredients' );
	}
}


/**
 *
 * @param bool     $post_has_changed If the post revision will be saved.
 * @param \WP_Post $last_revision    The last revision of the post.
 *
 * @return bool Whether or not a post revision must be saved.
 */
function save_revision_because_ingredients_has_changed(
	bool $post_has_changed,
	\WP_Post $last_revision
): bool {
	if ( $post_has_changed ) {
		return true;
	}

	// phpcs:ignore WordPress.Security -- No need to verify nonces.
	if ( ! isset( $_POST['we_aliment_ingredients'] ) ) {
		return false;
	}

	// phpcs:ignore WordPress.Security -- The text is filtered.
	$new_ingredients = $_POST['we_aliment_ingredients'];
	$new_ingredients = filter_ingredients_text( $new_ingredients );
	$old_ingredients = get_post_meta(
		$last_revision->ID,
		'we_ingredients',
		true
	);

	if ( $old_ingredients !== $new_ingredients ) {
		return true;
	}

	return false;
}


/**
 * Add the ingredients in the revision fields.
 *
 * @param array $fields The previous fields set.
 *
 * @return array The new fields, with ingredients added.
 */
function add_ingredients_revision_fields( array $fields ): array {
	$fields['we_ingredients'] = _x( 'Ingredients', 'backend', 'wiki-eat' );
	return $fields;
}

/**
 * Customize the display of the difference between ingredients section,
 * by making decoding HTML.
 *
 * @param array $diff_sections The diff array structure.
 *
 * @return array The modified diff array.
 */
function custom_display_ingredients_diff_section( array $diff_sections ): array {
	foreach ( $diff_sections as $index => $diff_section ) {
		if ( isset( $diff_section['id'] ) && 'we_ingredients' === $diff_section['id'] ) {
			if ( isset( $diff_section['diff'] ) ) {
				$decoded_section = htmlspecialchars_decode( $diff_section['diff'] );
				$diff_sections[ $index ]['diff'] = $decoded_section;
			}
		}
	}

	return $diff_sections;
}
