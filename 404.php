<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   28-09-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 26-06-2017

/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Nomades_Projets
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
					<h1 class="page-title text-center">
						Cette page n'existe pas ou à été supprimée
					</h1>
					<p class="text-center">
						La page que vous avez demandée n’a pas été trouvée. <br>
						Il se peut que le lien que vous avez utilisé soit rompu ou que vous ayez tapé l’adresse (URL) incorrectement.
					</p>
					<p class="text-center">
						<a href="<?php echo site_url();?>">revenir à la page d’accueil</a>.
					</p>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
