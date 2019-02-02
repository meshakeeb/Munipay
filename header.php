<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="wrapper">

	<header>

		<div class="container">

			<div class="row align-items-center">

				<div class="col-5">
					<a href="<?php home_url(); ?>" class="logo"><img src="<?php echo munipay()->assets() . '/images/logo.png'; ?>" class="img-responsive"></a>
				</div>

				<div class="col-7">
					<span class="link"><?php esc_html_e( 'ERICSSON CHECK REQUEST PORTAL', 'munipay' ); ?></span>
				</div>

			</div>

		</div>

	</header>

	<nav class="main-navbar navbar navbar-expand-md navbar-dark">

		<div class="container">

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<?php
			wp_nav_menu(
				[
					'theme_location'  => 'main_navigation',
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarNav',
					'menu_class'      => 'navbar-nav ml-auto',
					'walker'          => new \Munipay\Bootstrap_Walker,
				]
			);
			?>

		</div>

	</nav>
