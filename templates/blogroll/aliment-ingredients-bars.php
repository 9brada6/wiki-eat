<?php

$ingredients_calculator = new WE\Blogroll\Ingredients_Calculator();

$ingredients_status = $ingredients_calculator->get_ingredients_status();

$ingredients_num = $ingredients_calculator->get_ingredients_num();

if ( $ingredients_num === 0 ) {

	$unknown_bar_title = _x( 'The ingredients bar couldn\'t be calculated', 'ingredients-bar', 'wiki-eat' );

	?>
		<div class="blogroll-aliment-unknown-bar progress mt-0 mt-md-3 mt-lg-0 mb-4 flex-grow-1 flex-lg-grow-0" data-toggle="tooltip" tabindex="0" data-placement="bottom" title="<?= esc_html( $unknown_bar_title ) ?>">
			<div
				class="progress-bar opacity-50"
				role="progressbar"
				style="width: 33%;"
				aria-valuemin="0"
				aria-valuemax="100"
			>
			?
			</div>
			<div
				class="progress-bar opacity-50 bg-warning"
				role="progressbar"
				style="width: 34%;"
				aria-valuemin="0"
				aria-valuemax="100"
			>
				?
			</div>
			<div
				class="progress-bar opacity-50 bg-danger"
				role="progressbar"
				style="width: 33%;"
				aria-valuemin="0"
				aria-valuemax="100"
			>
			?
			</div>
		</div>
	<?php

	return;
}

$natural_healthy_num = $ingredients_calculator->get_natural_healthy_num();
$natural_moderate_num = $ingredients_calculator->get_natural_moderate_num();
$natural_unhealthy_num = $ingredients_calculator->get_natural_unhealthy_num();

$artificial_healthy_num = $ingredients_calculator->get_artificial_healthy_num();
$artificial_moderate_num = $ingredients_calculator->get_artificial_moderate_num();
$artificial_unhealthy_num = $ingredients_calculator->get_artificial_unhealthy_num();



$ingredients_natural_healthy_percent = round( $natural_healthy_num / $ingredients_num * 100 );
$ingredients_artificial_healthy_percent = round( $artificial_healthy_num / $ingredients_num * 100 );

$ingredients_natural_moderate_percent = round( $natural_moderate_num / $ingredients_num * 100 );
$ingredients_artificial_moderate_percent = round( $artificial_moderate_num / $ingredients_num * 100 );

$ingredients_natural_unhealthy_percent = round( $natural_unhealthy_num / $ingredients_num * 100 );
$ingredients_artificial_unhealthy_percent = round( $artificial_unhealthy_num / $ingredients_num * 100 );

if ( $ingredients_natural_healthy_percent > 0 && $ingredients_natural_healthy_percent < 10 ) {
	$ingredients_natural_healthy_percent = 10;
}

if ( $ingredients_artificial_healthy_percent > 0 && $ingredients_artificial_healthy_percent < 10 ) {
	$ingredients_artificial_healthy_percent = 10;
}

if ( $ingredients_natural_moderate_percent > 0 && $ingredients_natural_moderate_percent < 10 ) {
	$ingredients_natural_moderate_percent = 10;
}

if ( $ingredients_artificial_moderate_percent > 0 && $ingredients_artificial_moderate_percent < 10 ) {
	$ingredients_artificial_moderate_percent = 10;
}

if ( $ingredients_natural_unhealthy_percent > 0 && $ingredients_natural_unhealthy_percent < 10 ) {
	$ingredients_natural_unhealthy_percent = 10;
}

if ( $ingredients_artificial_unhealthy_percent > 0 && $ingredients_artificial_unhealthy_percent < 10 ) {
	$ingredients_artificial_unhealthy_percent = 10;
}

$healthy_bar_title = sprintf(
	'%s %s, %s.',
	_x( 'Healthy ingredients:', 'ingredients-bar', 'wiki-eat' ),
	sprintf( _nx( '%s is natural', '%s are natural', $natural_healthy_num, 'ingredients-bar', 'wiki-eat' ), $natural_healthy_num ),
	sprintf( _nx( '%s is artificial', '%s are artificial', $artificial_healthy_num, 'ingredients-bar', 'wiki-eat' ), $artificial_healthy_num )
);

$moderate_bar_title = sprintf(
	'%s %s, %s.',
	_x( 'Moderate health ingredients:', 'ingredients-bar', 'wiki-eat' ),
	sprintf( _nx( '%s is natural', '%s are natural', $natural_moderate_num, 'ingredients-bar', 'wiki-eat' ), $natural_moderate_num ),
	sprintf( _nx( '%s is artificial', '%s are artificial', $artificial_moderate_num, 'ingredients-bar', 'wiki-eat' ), $artificial_moderate_num )
);

$unhealthy_bar_title = sprintf(
	'%s %s, %s.',
	_x( 'Unhealthy ingredients:', 'ingredients-bar', 'wiki-eat' ),
	sprintf( _nx( '%s is natural', '%s are natural', $natural_unhealthy_num, 'ingredients-bar', 'wiki-eat' ), $natural_unhealthy_num ),
	sprintf( _nx( '%s is artificial', '%s are artificial', $artificial_unhealthy_num, 'ingredients-bar', 'wiki-eat' ), $artificial_unhealthy_num )
);

?>

<div class="blogroll-aliment-healthy-bar progress mt-0 mt-md-3 mt-lg-0 mb-4 mx-2 mx-lg-0 flex-grow-1 flex-lg-grow-0" data-toggle="tooltip" tabindex="0" data-placement="bottom" title="<?= esc_html( $healthy_bar_title ) ?>">
  	<div
		class="progress-bar overflow-hidden"
		role="progressbar"
		style="width: <?= esc_attr( $ingredients_natural_healthy_percent + $ingredients_artificial_healthy_percent ) ?>%;"
		aria-valuenow="<?= esc_attr( $ingredients_natural_healthy_percent + $ingredients_artificial_healthy_percent ) ?>"
		aria-valuemin="0"
		aria-valuemax="100"
	>
		<?= esc_html( $natural_healthy_num + $artificial_healthy_num) ?>
	</div>
</div>


<div class="blogroll-aliment-moderate-bar progress mt-0 mt-md-3 mt-lg-0 mb-4 mx-2 mx-lg-0 flex-grow-1 flex-lg-grow-0" data-toggle="tooltip" tabindex="0" data-placement="bottom" title="<?= esc_html( $moderate_bar_title ) ?>">
  	<div
		class="progress-bar bg-warning overflow-hidden"
		role="progressbar"
		style="width: <?= esc_attr( $ingredients_natural_moderate_percent + $ingredients_artificial_moderate_percent ) ?>%;"
		aria-valuenow="<?= esc_attr( $ingredients_natural_moderate_percent + $ingredients_artificial_moderate_percent ) ?>"
		aria-valuemin="0"
		aria-valuemax="100"
	>
		<?= esc_html( $natural_moderate_num + $artificial_moderate_num ) ?>
	</div>
</div>


<div class="blogroll-aliment-unhealthy-bar progress mt-0 mt-md-3 mt-lg-0 mx-2 mx-lg-0 flex-grow-1 flex-lg-grow-0" data-toggle="tooltip" tabindex="0" data-placement="bottom" title="<?= esc_html( $unhealthy_bar_title ) ?>">
  	<div
		class="progress-bar bg-danger overflow-hidden"
		role="progressbar"
		style="width: <?= esc_attr( $ingredients_natural_unhealthy_percent + $ingredients_artificial_unhealthy_percent ) ?>%;"
		aria-valuenow="<?= esc_attr( $ingredients_natural_unhealthy_percent + $ingredients_artificial_unhealthy_percent ) ?>"
		aria-valuemin="0"
		aria-valuemax="100"
	>
		<?= esc_html( $natural_unhealthy_num + $artificial_unhealthy_num ) ?>
	</div>
</div>
