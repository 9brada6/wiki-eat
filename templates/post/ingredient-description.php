<section id="ingredient-description" class="ingredient-section m-3">
	<h2 class="section-title">
		<?php _ex( 'Description:', 'post title', 'wiki-eat' ); ?>
	</h2>
	<?php
		if( trim( get_the_content() ) ) {
			the_content();
		} else {
			_e( 'No description for this ingredient available.', 'wiki-eat' );
		}
	 ?>
</section>
