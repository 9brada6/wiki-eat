<?php

namespace IA\Backend;

define( 'IA_ALIMENT_PRODUCER_FORM_INPUT_NAME', 'ia_company_of_aliment' );

define( 'IA_COMPANY_ALIMENTS_PRODUCED_META_NAME', 'ia_aliments_produced' );
define( 'IA_ALIMENT_CREATOR_COMPANY_META_NAME', 'ia_creator_company' );



// =======================================================
// = Register the relationship between posts.
// =======================================================

function register_aliment_brand_relationship() {
	\IA\One_To_Many_Post_Relationship::register_relationship( 'ia_aliment_brand', 'company', 'post' );
}
// add_action( 'init', '\\IA\\Backend\\register_aliment_brand_relationship' );



// =======================================================
// = Create Backend UI To Manage The Links
// =======================================================

function add_company_box_for_aliment() {
	add_meta_box(
		'ia_company_of_aliment',
		_x( 'Company:', 'backend', 'wiki-eat' ),
		'IA\\Backend\\create_company_box_for_aliment',
		'post',
		'normal',
		'high'
	);

}
// add_action( 'add_meta_boxes_post', 'IA\\Backend\\add_company_box_for_aliment' );


function create_company_box_for_aliment() {

	$post_relationship = new \IA\One_To_Many_Post_Relationship( 'ia_aliment_brand' );
	global $post;

	$company_id = $post_relationship->get_parent_id( $post->ID );
	$name = IA_ALIMENT_PRODUCER_FORM_INPUT_NAME;

	?>
		<p>
			<input type="search" placeholder="<?php _ex( 'Search for company', 'backend', 'wiki-eat' ) ?>">
			<input id="ia-company-of-aliment" name="<?php echo $name; ?>" type="search" value="<?php echo $company_id; ?>" data-swplive="true">
		</p>
	<?php
}



function update_aliment_post_on_save( $aliment_id ) {

	if( array_key_exists( IA_ALIMENT_PRODUCER_FORM_INPUT_NAME, $_POST)):
		$company_id = intval( $_POST[IA_ALIMENT_PRODUCER_FORM_INPUT_NAME]);

		$post_relationship = new \IA\One_To_Many_Post_Relationship('ia_aliment_brand' );

		if ( 0 === $company_id ) :
			$post_relationship->remove_relationship($aliment_id);
		else :
			$post_relationship->add_relationship($company_id,$aliment_id );
		endif;

	endif;

}
// add_action('save_post_post','\\IA\\Backend\\update_aliment_post_on_save');
