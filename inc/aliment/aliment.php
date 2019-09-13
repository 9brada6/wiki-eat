<?php
/**
 * Execute all the functions, do all actions, and add all
 * the filters for the Aliment Post Type.
 *
 * @todo Add Auto save actions that will save the meta.
 * @todo Save the images for each post revisions meta.
 *
 * @package Wiki_Eat
 */

declare( strict_types = 1 );

namespace WE\Aliment;

/**
 * Create the "Aliment" post type, by masking the default WordPress "post".
 */
add_filter(
	'register_post_type_args',
	'\\WE\\Aliment\\modify_default_post_type_to_aliment',
	10,
	2
);


/**
 * Disables the Gutenberg editor.
 */
add_filter( 'use_block_editor_for_post', '__return_false', 10 );



// =============================================================================
// Create Image Section
// =============================================================================

/**
 * On each aliment post edit, add a button where a user can select
 * images to an aliment.
 */
add_action(
	'edit_form_after_title',
	'\\WE\\Aliment\\add_aliment_images_selector'
);

// Save the images.
add_action( 'save_post', '\\WE\\Aliment\\save_aliment_images' );


/**
 * When restoring a revision, a newly revision is created from the old post,
 * the "save_post" will not trigger, so we need to hook elsewhere.
 * Save the new revision, with post meta from the old post.
 */
add_action(
	'_wp_put_post_revision',
	'\\WE\\Aliment\\save_images_on_revision_restore'
);

/**
 * When a revision is restored, update the main post meta with the one
 * from the revision.
 */
add_action(
	'wp_restore_post_revision',
	'\\WE\\Aliment\\restore_images_on_revision',
	10,
	2
);

/**
 * Make the revisions to be differentiable by the images meta.
 */
add_filter(
	'_wp_post_revision_fields',
	'\\WE\\Aliment\\add_images_to_revision_fields'
);

/**
 * Modify the revision text, to be easier to be targeted by the
 * regex that we will use to inject images instead of id's.
 */
add_filter(
	'_wp_post_revision_field_we_aliment_images',
	'\\WE\\Aliment\\wrap_ui_diff_text_for_regex_find'
);

/**
 * When displaying the difference between post revisions, make sure to output
 * the aliment images nicely.
 */
add_filter(
	'wp_get_revision_ui_diff',
	'\\WE\\Aliment\\custom_display_images_diff_section'
);



// =============================================================================
// Create Ingredients Section
// =============================================================================

/**
 * On each aliment post edit, add an editor where all the ingredients must
 * be written in.
 */
add_action( 'edit_form_after_title', '\\WE\\Aliment\\add_ingredient_editor' );

/**
 * Save the ingredients when the post is updated, this will also save
 * a meta for the revision.
 */
add_action( 'save_post', '\\WE\\Aliment\\save_ingredients_text' );


/**
 * When restoring a revision, a newly revision is created from the old post,
 * the "save_post" will not trigger, so we need to hook elsewhere.
 * Save the new revision, with post meta from the old post.
 */
add_action(
	'_wp_put_post_revision',
	'\\WE\\Aliment\\save_ingredients_text_on_revision_restore'
);

/**
 * When a revision is restored, update the main post meta with the one
 * from the revision.
 */
add_action(
	'wp_restore_post_revision',
	'\\WE\\Aliment\\restore_ingredients_on_revision',
	10,
	2
);

/**
 * Make sure that a revision is saved if only the ingredients has been changed.
 */
add_filter(
	'wp_save_post_revision_post_has_changed',
	'\\WE\\Aliment\\save_revision_because_ingredients_has_changed',
	10,
	3
);


/**
 * Make the revisions to be differentiable by the ingredients meta.
 */
add_filter(
	'_wp_post_revision_fields',
	'\\WE\\Aliment\\add_ingredients_revision_fields'
);

/**
 * When displaying the difference between post revisions, make sure to output
 * the ingredients text with no escaped HTML, or as it printed in frontend.
 */
add_filter(
	'wp_get_revision_ui_diff',
	'\\WE\\Aliment\\custom_display_ingredients_diff_section'
);
