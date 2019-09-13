<section id="ingredients-list" class="m-3">
	<h2 class="section-title">
		<?php _ex( 'Ingredients:', 'front-end, aliment post type, section title', 'wiki-eat' ); ?>
	</h2>

	<p class="lead">
		<?php
			$ingredients_text = WE\Aliment\get_ingredients_text();
			if ( trim( $ingredients_text ) ) {
				echo $ingredients_text;
			} else {
				echo _e( 'No ingredients for this aliment added.', 'wiki-eat' );
			}
		?>
	</p>
</section>
