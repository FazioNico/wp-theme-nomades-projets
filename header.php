<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nomades_Projets
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site container">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'nomades-projets' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<div class="row">
				<div class="col-sm-4">
					<h1 class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							TRAVAUX DE PROJET
							<?php //bloginfo( 'name' ); ?>
						</a>
					</h1>
				</div>
				<div class="col-sm-4">
					<div id="header-logo">
						<img src="<?php echo get_template_directory_uri();?>/src/img/logo-nomades-small-gray.png" alt="logo Nomades" />
						<p>
							nomades.ateliers
						</p>
					</div>
				</div>
				<div class="header-search-form col-sm-4">
					<?php get_search_form();?>
				</div>
			</div>

			<div class="row">

				<div class="col-xs-12">
					<hr class="separation-header" />
				</div>

			</div><!-- .row -->


		</div><!-- .site-branding row-->


	</header><!-- #masthead -->

	<div id="content" class="site-content">
