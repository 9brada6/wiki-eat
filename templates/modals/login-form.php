<div
	class="modal fade"
	id="login-modal"
	tabindex="-1"
	role="dialog"
	aria-labelledby="<?php _ex( 'Login form popup', 'accessibility', 'wiki-eat' ); ?>"
	aria-hidden="true"
>

	<div class="modal-dialog" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="exampleModalLabel">
				<?php if ( is_user_logged_in() ) : ?>
					<?php _ex( 'Options', 'modal title', 'wiki-eat' ); ?>
				<?php else : ?>
					<?php _ex( 'Login', 'modal title', 'wiki-eat' ); ?>
				<?php endif; ?>

			</h5>
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        		<span aria-hidden="true">&times;</span>
        	</button>
      	</div>

    	<div class="modal-body p-md-4">
			<?php if ( is_user_logged_in() ) : ?>

				<div class="mb-4">
					<?= get_avatar( get_current_user_id(), 100 ) ?>

					<div class="d-inline-flex flex-column align-top ml-3">

						<p class="h5 mb-3 pl-1 text-left">
							<?php
								$current_user = wp_get_current_user();
								echo esc_html( $current_user->display_name );
							?>
						</p>

						<div class="btn-group">
							<a class="btn btn-secondary px-5" href="<?= esc_url( admin_url( 'profile.php' ) ) ?>">
								<?php _ex( 'My profile', 'login modal', 'wiki-eat' ) ?>
							</a>

							<a class="btn btn-outline-secondary" href="<?= wp_logout_url() ?>">
								<?php _ex( 'Logout', 'logout modal', 'wiki-eat' ) ?>
							</a>
						</div>
					</div>
				</div>


				<a class="btn btn-primary d-block" href="<?= esc_url( admin_url( 'edit.php' ) ) ?>">
					<?php _ex( 'Edit Aliments', 'login modal', 'wiki-eat' ) ?>
				</a>

			<?php else: ?>

				<?php
					echo do_shortcode( '[nextend_social_login provider="facebook" align="center"]' );
				?>

			<?php endif; ?>

    	</div>
    </div>
	</div>

</div>
