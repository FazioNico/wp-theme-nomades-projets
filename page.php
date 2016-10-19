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
      <?php
        get_template_part('template-parts/navigation', 'page');
        /// Bof - page
        if ( have_posts() ) :
          while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content', 'page' );
          endwhile; // End of the loop.
        else :
    			get_template_part( 'template-parts/content', 'none' );
    		endif;
      ?>
		</div>
	</div>
  <div class="row">
    <div class="col col-sm-12">
      <main id="main" class="site-main index" role="main">
        <?php
          /// Bof - while custom post type
          $projects_query = array(
            'post_type' => 'projet',
            'posts_per_page' => '20',
            'order' => 'ASC',
          	'orderby'   =>  array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
          	'meta_key'  => 'mention'
          );
          query_posts($projects_query);
          while ( have_posts() ) : the_post();
            $customMetaPost = get_post_custom($post->ID);
            $mention = $customMetaPost["mention"][0];
            switch ($mention) {
              case 0:
                print_r('Travaux non certifiés '.$mention);
                get_template_part('template-parts/content', 'projects');
                break;
              case 1:
                print_r('Certification '.$mention);
                get_template_part('template-parts/content', 'projects');
                break;
              case 2:
                print_r('Mention Bien '.$mention);
                get_template_part('template-parts/content', 'projects');
                break;
              case 3:
                print_r('Mention Très Bien '.$mention);
                get_template_part('template-parts/content', 'projects');
                break;
            }
          endwhile;
          //// Eof - while custom post type
        ?>
      </main>
    </div>
  </div>
</div>


<?php
//get_sidebar();
get_footer();
