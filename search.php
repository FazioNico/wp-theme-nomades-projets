<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );
			endwhile;
			the_posts_navigation();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif; ?>
</main><!-- #main -->
<?php
//get_sidebar();
get_footer();
