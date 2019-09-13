
<?php hierarchical_category_tree(); ?>


<?php
function hierarchical_category_tree( $cat = 0 ) {

	$categories = get_categories( array( 'parent' => $cat ) );

	echo '<ul class="list-unstyled ml-3">';

	if( $categories ) :
		foreach( $categories as $cat ) :
			echo '<li>';
				echo '<p class="mb-2">';
					echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">';
						echo esc_html( $cat->name );
					echo '</a>';
				echo '</p>';

				hierarchical_category_tree( $cat->term_id );
			echo '</li>';
		endforeach;
	endif;

	echo '</ul>';

}
?>
