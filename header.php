<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> style="background-color: #eee">

	<?php do_action( 'we_after_opening_body_tag' ); ?>

	<?php get_template_part( 'templates/modals/login-form' ); ?>

	<header class="mb-5">
		<?php get_template_part( 'templates/navbar/navbar' ); ?>
	</header>
