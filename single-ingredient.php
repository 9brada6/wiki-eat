<?php get_header(); ?>
<?php
	if ( have_posts() ) {
		the_post();
	}
?>

<main>
	<div class="container article-container my-3">
		<div class="row">
			<div class="col-lg-8">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white border rounded shadow' ); ?>>
					<?php get_template_part( 'templates/post/aliment-header' ); ?>

					<?php get_template_part( 'templates/post/aliment-description' ); ?>

					<?php get_template_part( 'templates/post/aliment-comments' ); ?>
				</article>
			</div> <!-- .col-lg-8 -->

			<div class="col-lg-4">
				<aside class="bg-white border rounded shadow overflow-hidden">
				<ul class="nav nav-tabs mt-3 ml-2" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="aliments-aside-tab" data-toggle="tab" href="#aliments-aside-wrapper" role="tab" aria-controls="aliments-aside-wrapper" aria-selected="true">
							<?php _ex( 'Aliments', 'aside tabs title', 'wiki-eat' ); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="categories-aside-tab" data-toggle="tab" href="#categories-aside-wrapper" role="tab" aria-controls="categories-aside-wrapper" aria-selected="true">
							<?php _ex( 'Categories', 'aside tabs title', 'wiki-eat' ); ?>
						</a>
					</li>
				</ul>

				<div class="tab-content" id="aside-tab-content-wrapper">
					<div id="aliments-aside-wrapper" class="tab-pane flash show active bg-white pt-1">
						<?php get_template_part( 'templates/aside/aliments-aside-roll' ); ?>
					</div>

					<div id="categories-aside-wrapper" class="tab-pane flash bg-white pt-3" style="padding-bottom: 1px;">
						<?php get_template_part( 'templates/aside/aliments-aside-categories' ); ?>
					</div>
				</div>
				</aside>
			</div> <!-- .col-lg-4 -->
		</div> <!-- .row -->
	</div>
</main>

<?php get_footer(); ?>
