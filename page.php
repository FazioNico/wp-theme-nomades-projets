<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */

get_header(); ?>
<div class="conatainer">
  <div class="row">
    <div class="col col-sm-4">
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
		</div>
    <div class="col col-sm-8">
			<main id="main" class="site-main index" role="main">
			  <?php
			    /// Bof - page
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
			    //// Eof - while custom post type
			  ?>
			</main>
		</div>
	</div>
</div>


<?php
//get_sidebar();
get_footer();
