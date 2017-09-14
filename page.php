<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   28-09-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 31-07-2017

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

                  $current = get_the_ID($post->ID);
                  $cargs = array(
                       'child_of'      => 0,
                        // 'orderby'           => array( 'meta_value_num' => 'DESC', 'name' => 'ASC' ),
                        // 'meta_key'          => 'mentions',
                       'orderby'       => 'name',
                       'order'         => 'DESC',
                       'hide_empty'    => 1,
                       'posts_per_page' => 3,
                       'taxonomy'      => 'category', //change this to any taxonomy
                   );
                   foreach (get_categories($cargs) as $tax) :
                       // List posts by the terms for a custom taxonomy of any post type
                       $args = array(
                           'post_type' => 'projet',
                           'post_status' => 'publish',
                           //'meta_value'        => $current,
                           'order' => 'DESC',
                           'orderby'   =>  array( 'category' => 'DESC' ),
                           'meta_key'  => 'project_year',
                           'posts_per_page' => 3,
                           'tax_query' => array(
                               array(
                                   'taxonomy'  => 'category',
                                   'field'     => 'slug',
                                   'terms'     => $tax->slug
                               )
                           )
                       );
                       if (get_posts($args)) :
                   ?>
                       <h2><?php echo $tax->name; ?></h2>
                           <?php
                            $count = 0;
                            foreach(get_posts($args) as $post) :
                              $count = $count+1;
                              if($count <= 3){
                                get_template_part('template-parts/content', 'projects');
                              }
                            endforeach;

                       endif;
                   endforeach;
          // $projects_query = array(
          //   'post_type' => 'projet',
          //   'posts_per_page' => '20',
          //   'order' => 'ASC',
          // 	'orderby'   =>  array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
          // 	'meta_key'  => 'mention'
          // );
          // query_posts($projects_query);
          // while ( have_posts() ) : the_post();
          //   get_template_part('template-parts/content', 'projects');
          // endwhile;
          //// Eof - while custom post type
        ?>
      </main>
    </div>
  </div>
</div>


<?php
//get_sidebar();
get_footer();
