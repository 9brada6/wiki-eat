<?php
/**
 * Creates a section, where the users will can select Images
 * that will be specific to an aliment.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Aliment;

/**
 * Creates the HTML for the backend of aliments
 * edit page, which lets users to select images and order
 * them.
 *
 * @param \WP_Post $post A post object.
 *
 * @return void
 */
function add_aliment_images_selector( \WP_Post $post ) {

	if ( get_post_type( $post ) !== 'post' ) {
		return;
	}

	$images_ids = get_aliment_images( $post );

	?>
		<h2 style="font-size: 1.5rem;">
			<?php _ex( 'Select images for aliment:', 'backend', 'wiki-eat' ); ?>
		</h2>
		<div style="padding: 16px; background-color: white; border: 1px solid #ddd; margin: 16px 0;">

			<button
				id="we-set-aliment-images"
				class="button button-primary"
				type="button"
				data-script="we.CustomMediaSelection"
				data-hidden-input="#we-aliment-images"
				data-thumbnails-container="#we-aliment-images-box"
				data-frame-title="<?php _ex( 'Select images for aliment', 'backend', 'wiki-eat' ); ?>"
				data-frame-btn-text="<?php _ex( 'Use this image', 'backend', 'wiki-eat' ); ?>"
			>
				<?php _ex( 'Set images for aliments', 'backend', 'wiki-eat' ); ?>
			</button>
			<input id="we-aliment-images" type="hidden" name="aliment_images_input" value="<?php echo esc_attr( $images_ids ); ?>">

			<div id="we-aliment-images-box" style="margin: 16px 0 0;">
				<p id="we-aliment-images-no-img" class="we-no-images" style="margin-bottom: 0;">
					<?php _ex( 'No image for aliments selected.', 'backend', 'wiki-eat' ); ?>
				</p>
			</div>
		</div>
	<?php
}

/**
 * Get the aliment images array.
 *
 * @param \WP_Post|int|null $post Optional. The post to retrieve the images.
 *                                Defaults to global post.
 *
 * @return int[] The sanitized array with images ids.
 */
function get_aliment_images( $post = null ): string { // phpcs:ignore NeutronStandard
	$post = get_post( $post );

	$images_ids = get_post_meta( $post->ID, 'we_aliment_images', true );

	if ( ! is_string( $images_ids ) ) {
		return '';
	}

	return sanitize_aliment_images( $images_ids );
}


/**
 * Ensures that the aliment images are saved.
 *
 * This function must be called at 'save_post' hook.
 *
 * @param int $post_id The post ID for which to save.
 *
 * @return void
 */
function save_aliment_images( int $post_id ) {
	// phpcs:ignore WordPress.Security
	if ( ! array_key_exists( 'aliment_images_input', $_POST ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security
	$aliment_images = $_POST['aliment_images_input'];
	if ( ! is_string( $aliment_images ) ) {
		return;
	}
	$aliment_images = sanitize_aliment_images( $aliment_images );

	update_post_meta( $post_id, 'we_aliment_images', $aliment_images );

	// Also save a metadata for the revision.
	$parent_id = wp_is_post_revision( $post_id );
	if ( $parent_id ) {
		add_metadata( 'post', $post_id, 'we_aliment_images', $aliment_images );
	}
}

/**
 * Make sure that the array contains only IDs.
 *
 * @param array $images An array with unsafe images ids.
 *
 * @return array An array that contains only valid id's.
 */
function sanitize_aliment_images( string $images ): string {

	$images = explode( ';', $images );

	if ( ! is_countable( $images ) ) {
		return array();
	}

	$sanitized_ids_array = array();

	foreach ( $images as $image_id ) {
		if ( is_numeric( $image_id ) ) {
			$image_id = absint( $image_id );
			if ( $image_id > 0 ) {
				array_push( $sanitized_ids_array, $image_id );
			}
		}
	}

	$sanitized_ids_array = join( ';', $sanitized_ids_array );

	return $sanitized_ids_array;
}

/**
 * Add the images in the revision fields.
 *
 * @param array $fields The previous fields set.
 *
 * @return array The new fields, with ingredients added.
 */
function add_images_to_revision_fields( array $fields ): array {
	$fields['we_aliment_images'] = _x( 'Images', 'backend', 'wiki-eat' );
	return $fields;
}

/**
 * Save the images when a revision is restored back.
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
function save_images_on_revision_restore( int $post_id ) {
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
	$ingredients = get_metadata( 'post', $revision_id, 'we_aliment_images', true );

	add_metadata( 'post', $post_id, 'we_aliment_images', $ingredients );
}

/**
 * When a revision is restored, also restore the images data.
 *
 * @param int $post_id     The newly post id.
 * @param int $revision_id The revision post id.
 *
 * @todo
 * @return void
 */
function restore_images_on_revision( int $post_id, int $revision_id ) {
	$ingredients = get_metadata( 'post', $revision_id, 'we_aliment_images', true );

	if ( false !== $ingredients ) {
		update_post_meta( $post_id, 'we_aliment_images', $ingredients );
	} else {
		delete_post_meta( $post_id, 'we_aliment_images' );
	}
}

function wrap_ui_diff_text_for_regex_find( string $comparator ): string {
	$wrap_text = '<WE_ALIMENT_IMAGES>';
	return $wrap_text . $comparator . $wrap_text;
}

function custom_display_images_diff_section( array $diff_sections ): array {
	foreach ( $diff_sections as $diff_section_index => $diff_section ) {
		if ( isset( $diff_section['id'] ) && 'we_aliment_images' === $diff_section['id'] ) {
			if ( isset( $diff_section['diff'] ) ) {
				$matches = array();
				$wrap_text = '<WE_ALIMENT_IMAGES>';
				$diff_section['diff'] = htmlspecialchars_decode( $diff_section['diff'] );
				preg_match_all( '/<WE_ALIMENT_IMAGES>(.*)<WE_ALIMENT_IMAGES>/', $diff_section['diff'], $matches );
				if ( isset( $matches[1] ) ) :
					foreach( $matches[1] as $index => $match ){
						$matches[1][ $index ] = wp_kses( $match, array() );
						$matches[1][ $index ] = get_images_src( explode( ';', $matches[1][ $index ] ) );
						foreach( $matches[1][ $index ] as $img_src_index => $img_src ){
							$matches[1][ $index ][ $img_src_index ] = '<img src="' . $img_src . '" width=150 height=150 />';
						}
						$matches[1][ $index ] = join( ' ', $matches[1][ $index ] );
					}
				endif;

				if ( ! array_key_exists( 0, $matches[1] ) ) {
					continue;
				}

				$diff_section['diff'] = preg_replace(
					'/<WE_ALIMENT_IMAGES>.*<WE_ALIMENT_IMAGES>/',
					$matches[1][0],
					$diff_section['diff'],
					1
				);

				$diff_sections[ $diff_section_index ]['diff'] = $diff_section['diff'];

				if ( ! array_key_exists( 1, $matches[1] ) ) {
					continue;
				}

				$diff_section['diff'] = preg_replace(
					'/<WE_ALIMENT_IMAGES>.*<WE_ALIMENT_IMAGES>/',
					$matches[1][1],
					$diff_section['diff'],
					1
				);

				$diff_sections[ $diff_section_index ]['diff'] = $diff_section['diff'];
			}
		}
	}

	return $diff_sections;
}


function get_images_src( array $images_ids, $format = 'thumbnail' ): array {
	$images_src = array();

	foreach( $images_ids as $index => $image_id ){
		$url = wp_get_attachment_image_url( (int) $image_id, $format );
		if ( $url ) {
			$images_src[ $index ] = $url;
		} else {
			$images_src[ $index ] = '';
		}
	}

	return $images_src;
}
