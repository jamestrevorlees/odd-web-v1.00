<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ODDV1pt0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'oddv1pt0' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<nav class="navbar navbar-default navbar-header navbar-fixed-top">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img alt="Brand" src="...">
				</a>
			</div>
		</nav>


			<?php

			if ( is_front_page() && is_home() ) : ?>
	<section>
				<video autoplay loop poster="cookies.jpg" id="bg-video">
					<source src="/wp-content/uploads/Open Data Wed Header_Colour_1535x400.mp4" type="video/webm">
					<source src="/wp-content/uploads/Open Data Wed Header_Colour_1535x400.mp4" type="video/mp4">
				</video>
	</section>

			<?php else : ?>

				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>

			<?php
			endif;
			?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'oddv1pt0' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->

	<div id="content" class="site-content">


