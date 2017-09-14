<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   28-09-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 14-09-2017

/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */

get_header(); ?>
<div class="conatainer hide">

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
        print_r('
          <section>
            <div class="entry-content">
              <h1 class="hide">'.single_cat_title("", false).'</h1>
              '.category_description().'
            </div>
          </section>
        ');

      ?>
    </div>
  </div>
  <div class="row">
    <div class="col col-sm-12">
      <main id="main" class="site-main archive" role="main">
      		<?php
          $cat = get_category( get_query_var( 'cat' ) );
          $args = array(
              'post_type' => 'projet',
              'post_status' => 'publish',
              //'meta_value'        => $current,
              'order' => 'DESC',
              'orderby'   =>  array( 'project_year' => 'DESC' ),
              'meta_key'  => 'project_year',
              'posts_per_page' => 300,
              'tax_query' => array(
                  array(
                      'taxonomy'  => 'category',
                      'field'     => 'slug',
                      'terms'     => $cat->slug
                  )
              )
          );
          query_posts($args);

          //print_r($cat->slug);
      		if ( have_posts() ) :
      			/* Start the Loop */
      			while ( have_posts() ) : the_post();
              get_template_part('template-parts/content', 'projects');
      			endwhile;
      			the_posts_navigation();
      		else :
      			get_template_part( 'template-parts/content', 'none' );
      		endif;
          wp_reset_query();
          ?>

      </main><!-- #main -->
    </div>
  </div>
</div>


<?php
//get_sidebar();
get_footer();
