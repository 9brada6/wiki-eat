<?php get_header(); ?>

<main>
	<div class="container article-container my-3">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="p-4 bg-white border rounded shadow">
					<div class="mb-5 text-center">
						<img width="300" src="<?= esc_url( WE\Functions\get_404_src() ) ?>" />
					</div>
					<h1 class="h3">
						Ne pare rău, dar se pare că ați
						accesat o pagină care nu există.
						Puteți merge înapoi
						<a href="<?= home_url( '/' ) ?>">acasă</a>.
					</h1>
				</div>
			</div> <!-- .col-lg-8 -->
		</div> <!-- .row -->
	</div>
</main>

<?php get_footer(); ?>
