<?php

namespace WE\Post;

function display_comments(){
	global $wp;
	$url = home_url( $wp->request );

	?>
		<div class="fb-comments" data-href="<?= $url ?>" data-width="100%" data-numposts="10">
		</div>
	<?php
}

function enqueue_facebook_comments_script() {
	$locale = get_locale();
	if ( ! $locale ) {
		$locale = 'en_GB';
	}

	$url = 'https://connect.facebook.net/' . $locale . '/sdk.js#xfbml=1&version=v4.0&appId=718922771822661&autoLogAppEvents=1';

	?>
		<div id="fb-root"></div>
		<script
			async
			defer
			crossorigin="anonymous"
			src="<?= esc_url( $url ) ?>"
		>
		</script>
	<?php
}

add_action( 'we_after_opening_body_tag', '\\WE\\Post\\enqueue_facebook_comments_script' );
