<?php get_header(); ?>

<?php if( have_posts() ) : ?>

	<main id='blogroll-container' class="container">
		<div class="row justify-content-center">

			<?php
				while ( have_posts() ) :
					the_post();

					if ( 'post' === get_post_type() ) {
						get_template_part( 'templates/blogroll/aliment' );
					} elseif ( 'ingredient' === get_post_type() ) {
						get_template_part( 'templates/blogroll/ingredient' );
					}
				endwhile;
			?>

		</div>

		<div class="row mt-4">
			<div class="col-12 d-flex justify-content-center">
				<?php bootstrap_pagination(); ?>
			</div>
		</div>
	</main>

<?php endif; ?>

<?php
	get_footer();
