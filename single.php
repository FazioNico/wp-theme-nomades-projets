<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
<main id="main" class="site-main single" role="main">
  <?php
    get_template_part('template-parts/navigation', 'page');
  ?>
  <?php
		while ( have_posts() ) : the_post();
			if ($post->post_type == 'projet'){
				get_template_part( 'template-parts/single', 'projet' );
			}
			else {
				get_template_part( 'template-parts/content', get_post_format() );
				the_post_navigation();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			}
		endwhile; // End of the loop.
		?>
</main><!-- #main -->
<?php
  get_footer();
