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

					<div class="p-3">

						<h1>
						Wiki-Eat
							<img id="main-navbar-logo" class="mr-1 ml-2 align-bottom" src="<?php echo esc_url( WE\Functions\get_svg_icon_uri() ); ?>" height="40" style="margin-bottom: 0.1rem">

						</h1>

						<p class="h3 text-muted">Tu știi ce mănânci?</p>

						<p class="h5 mt-4 mb-4">
							Wiki-Eat a apărut din ideea de a exista un website
							care să colecționeze o bază de date cu foarte multe
							alimente, precum și cu ingredientele lor.
						</p>

						<p class="h5 mb-4">
							Oricine este binevenit să modifice orice aliment
							sau ingredient, atâta timp cât informațiile corespund
							cu ceea ce este scris pe etichetă.
						</p>

						<p class="h5 mb-3">
							Pentru ca toate articolele să aibe același stil
							și să nu existe o diferență enormă între ele, se dau
							un set de sfaturi ce sunt foarte bine de urmat:
						</p>

						<div class="row">
							<div class="col-12 d-flex flex-column align-items-center justify-content-center p-4 bg-secondary text-white">
								<i class="fas fa-pizza-slice fa-4x mb-3"></i>
								<p>Sfaturi de editare a alimentelor</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">1</p>
								<p>
									Titlul nu ar trebui să conțină și cantitatea,
									eventual se poate specifica cantitățile în
									care vine un produs în descriere.
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white" style="opacity: 0.9">
								<p class="display-4">2</p>
								<p>
									Scrieți eticheta de ingrediente exact așa cum
									este și ea scrisă pe produs.
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">3</p>
								<p>
									Faceți referire la toate ingredientele. Verificați dacă
									există, și dacă nu, creați unul doar pentru referință.
									Nu e nevoie să îl modificați, altcineva va veni și face asta.
								</p>
							</div>
						</div>

						<div class="row">
							<div class="col-12 d-flex flex-column align-items-center justify-content-center p-4 bg-secondary text-white">
								<i class="fas fa-images fa-4x mb-3"></i>
								<p>Sfaturi de manipulare a imaginilor</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">1</p>
								<p>
									Încarcă imagini clare.
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white" style="opacity: 0.9">
								<p class="display-4">2</p>
								<p>
									O imagine cu ingredientele scrise pe etichetă
									este binevenită.
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">3</p>
								<p>
									Ține numărul imaginilor cât mai mic.
								</p>
							</div>
						</div>

						<div class="row">
							<div class="col-12 d-flex flex-column align-items-center justify-content-center p-4 bg-secondary text-white">
								<i class="fas fa-carrot fa-4x mb-3"></i>
								<p>Sfaturi de editare a ingredientelor</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">1</p>
								<p>
									Scrie în titlu toate denumurile, precum și
									numărul E-ului dacă există.
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white" style="opacity: 0.9">
								<p class="display-4">2</p>
								<p>
									În descriere sunt binevenite studiile în care
									a fost analizat aditivul, precum și alte
									caracteristici ce sunt legate de interzicere,
									doza letală... etc
								</p>
							</div>
							<div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center p-4 bg-primary text-white">
								<p class="display-4">3</p>
								<p>
									Pune gradul corect de sănătate pe care ingredientul îl are
									asupra omului.
								</p>
							</div>
						</div>

					</div>

				</article>
			</div>
		</div>
	</main>

<?php get_footer(); ?>
