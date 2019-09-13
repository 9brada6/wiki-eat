<article id='post-<?php the_ID(); ?>' <?php post_class( 'ia-blogroll-article card' ); ?>>

	<div class="card-body">

		<?php the_title( '<h5 class="card-title">', '<h5>' ); ?>

		<p class="card-text">
			Some quick example text to build on the card title and make up the bulk of the card's content.
		</p>

		<a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'To article', 'wiki-eat' ); ?></a>

	</div>

</article>
