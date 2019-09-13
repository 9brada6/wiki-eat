<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form class='search-form' role='search' method='get' action='<?php echo esc_url( home_url( '/' ) ); ?>'>
	<div class='input-group'>

		<input id='<?php echo $unique_id; ?>' class='form-control' data-swplive="true" placeholder='<?php _ex( 'Search...', 'top navigation bar, search placeholder', 'wiki-eat' ); ?>' type='search' name='s' value="<?php echo get_search_query(); ?>">

		<div class='input-group-append'>
			<button class='btn btn-outline-light'>
				<i class="fas fa-search"></i>
			</button>
		</div>

	</div>
</form>
