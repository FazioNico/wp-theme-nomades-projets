<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */

get_header(); ?>
<aside class="menu-secondary">
  <?php
    //// Bof - Menu side
    wp_nav_menu(array(
      'theme_location' => 'secondary',
      'container_class' => 'new_menu_class'
    ));
    //// Eof - Menu side
  ?>
</aside>
<main id="main" class="site-main archive" role="main">
		<?php
		if ( have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				 get_template_part('template-parts/content', 'projects');

			endwhile;
			the_posts_navigation();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif; ?>

</main><!-- #main -->
<?php
//get_sidebar();
get_footer();
