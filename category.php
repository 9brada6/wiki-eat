<?php get_header(); ?>

<?php if( have_posts() ) : ?>

	<main id='blogroll-container' class="container">
		<div class="row justify-content-center">

			<?php
				if ( have_posts() ) :

					$category = get_category( get_query_var( 'cat' ) );

					$category_name = '';
					if( $category instanceof \WP_Term ) :
						$category_name = $category->name;
					endif;

					echo "<h1 class='h3 mb-4'>";
						printf(
							_x( 'Aliments from category "%s"', 'No aliments in category', 'wiki-eat' ),
							$category_name
						);
					echo "</h1>";


					while ( have_posts() ) :
						the_post();

						if ( 'post' === get_post_type() ) {
							get_template_part( 'templates/blogroll/aliment' );
						} elseif ( 'ingredient' === get_post_type() ) {
							get_template_part( 'templates/blogroll/ingredient' );
						}
					endwhile;

				else :

					_ex( 'No aliments in this category', 'No aliments in category', 'wiki-eat' );

				endif;
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
