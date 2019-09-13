<?php
	global $post;

	$args = array(
		'numberposts' => 18,
		'exclude' => array( $post->ID ),
		'orderby' => 'rand',

	);
	$posts_array = get_posts( $args );
?>

<!-- <div class="aliments-roll-title px-3 my-3">
	<p class="h4 text-center">
		<?php // _ex( 'Other aliments to view', 'aside title', 'wiki-eat' ); ?>
	</p>
</div> -->

<?php foreach ( $posts_array as $post ) : ?>
	<div id="aside-post--<?php the_ID(); ?>" class="aside-post m-0 mt-3">
		<a class="d-block aside-post-wrapper-link" href="<?php the_permalink(); ?>">
			<p class="h5 px-3 pt-3 pb-2 m-0 text-decoration-none text-dark">
				<?php the_title(); ?>
			</p>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="embed-responsive embed-responsive-16by9">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'embed-responsive-item blogroll-thumbnail w-auto m-auto' ) ); ?>
				</div>
			<?php else : ?>
				<div class="embed-responsive embed-responsive-16by9">
					<div class="no-image-thumbnail embed-responsive-item d-flex flex-column align-items-center justify-content-center bg-dark text-white">
						<i class="fas fa-images fa-4x"></i>
						<p class="my-2">
							<?php _ex( 'No image available', 'No post thumbnail text', 'wiki-eat' ); ?>
						</p>
					</div>
				</div>
			<?php endif; ?>
		</a>
	</div>
<?php endforeach; ?>
