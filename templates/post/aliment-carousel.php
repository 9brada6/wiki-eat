<section id="aliments-photos" class="my-3">
	<?php
		$carousel = new WE\Post\Bootstrap_Carousel();
		$carousel->set_images( explode( ';', WE\Aliment\get_aliment_images() ) );
		if ( $carousel->can_be_displayed() ) {
			$carousel->display();
		} else {
			echo '<p class="px-3">';
				_e( 'No images for aliment available.', 'wiki-eat' );
			echo '</p>';
		}
	?>
</section>
