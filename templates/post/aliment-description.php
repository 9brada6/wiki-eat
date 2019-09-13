<section id="aliment-description" class="aliment-section m-3">
	<h2 class="section-title">
		<?php _ex( 'Description:', 'post title', 'wiki-eat' ); ?>
	</h2>
	<?php
		if( trim( get_the_content() ) ) {
			the_content();
		} else {
			_e( 'No description for this aliment available.', 'wiki-eat' );
		}
	 ?>
</section>
