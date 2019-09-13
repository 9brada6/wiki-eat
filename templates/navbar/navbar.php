<nav id="main-navbar" class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary shadow">

	<a id="main-navbar-brand" class="navbar-brand mr-lg-5" href="<?php echo esc_url( get_site_url() ); ?>" title="<?php _e( 'Back to home', 'wiki-eat' ); ?>" area-label="<?php _e( 'Back to home', 'wiki-eat' ); ?>">
		<img id="main-navbar-logo" class="mr-3" src="<?php echo esc_url( WE\Functions\get_svg_icon_uri() ); ?>" height="70" >

		<div id="main-navbar-brand-desc" class="d-inline-block text-white align-middle">
			<p class="h3 text-center mb-1">Wiki-Eat</p>
			<small class="d-block text-center">
				<?php _ex( 'Aliments wiki', 'main navbar', 'wiki-eat' ); ?>
			</small>
		</div>
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar-collapse" aria-controls="main-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div id="main-navbar-collapse" class="collapse navbar-collapse flex-wrap">

		<div id="main-navbar-searchform-container" class="flex-grow-1 mr-lg-5 py-3">
			<?php
				// Get the header searchform.
				get_template_part( 'templates/navbar/searchform' );
			?>
		</div>

		<div id="main-navbar-user-container" class="flex-shrink-0 mr-4 py-2">
			<?php if ( is_user_logged_in() ) : ?>
				<button class="btn btn-outline-light" data-toggle="modal" data-target="#login-modal">
					<?php echo get_avatar( get_current_user_id(), 27 ); ?>
					<i class="ml-2"></i>
					<?php _ex( 'My account', 'navbar button text', 'wiki-eat' ); ?>
				</button>
			<?php else : ?>
				<button class="btn btn-outline-light" data-toggle="modal" data-target="#login-modal">
					<i class="fas fa-user mr-2"></i>
					<?php _ex( 'Login', 'navbar button text', 'wiki-eat' ); ?>
				</button>
			<?php endif; ?>
		</div>

		<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'depth'          => 2,
				'menu_id'        => 'main-navbar-menu',
				'menu_class'     => 'navbar-nav flex-shrink-0 ml-auto mr-3',
				'container'      => false,
				'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback',
				'walker'         => new WP_Bootstrap_Navwalker(),
			) );
		?>

	</div>

</nav>
