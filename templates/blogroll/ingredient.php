<div class="col-12 col-md-11 my-3">
	<article id="blogroll-ingredient-<?php the_ID(); ?>" <?php post_class( 'd-flex flex-wrap align-items-center bg-white position-relative rounded shadow overflow-hidden' ); ?>>

		<div class="blogroll-aliment-image-container p-0 col-12 col-md-5 col-lg-4">
			<div class="embed-responsive embed-responsive-16by9">
				<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail(
							'medium',
							array( 'class' => 'blogroll-thumbnail embed-responsive-item w-auto m-auto')
						);
					} else {
						?>
							<div class="embed-responsive-item d-flex flex-column align-items-center justify-content-center w-100 h-100 bg-dark text-white">
								<i class="fas fa-image fa-4x"></i>
								<p class="mt-2">
									<?php _ex( 'No image available', 'blogroll', 'wiki-eat' ); ?>
								</p>
							</div>
						<?php
					}
				?>
			</div>
		</div>

		<div class="align-self-start position-static col-12 col-md-7 col-lg-8 py-3 py-md-2 py-lg-2 py-xl-3">
			<h2 class="h4 m-0 mb-lg-2 mb-xl-3"><?php the_title(); ?></h2>

			<p class="blogroll-aliment-description mb-2">
				<?= wp_kses( get_the_excerpt() , array() ); ?>
			</p>

			<a href="<?php the_permalink(); ?>" class="stretched-link">
				<?php _ex( 'To article', 'blogroll', 'wiki-eat' ); ?>
			</a>
		</div>

	</article>
</div>
