<?php get_header(); ?>
<?php
	if ( have_posts() ) {
		the_post();
	}
?>

	<main>
		<div class="container article-container my-5">
			<div class="row justify-content-center">
				<div class="col-12 col-lg-10">
				<article id="page-article" <?php post_class( 'bg-white border rounded shadow' ); ?>>
					<header class="px-3 my-4">
						<h1 class="text-center"><?php the_title(); ?></h1>
					</header>

					<section class="p-3">
						<?php the_content(); ?>
					</section>
				</article>
			</div>
		</div>
	</main>

<?php get_footer(); ?>
