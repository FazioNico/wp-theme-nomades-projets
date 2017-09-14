<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   04-10-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 14-09-2017

/*
	Template Name: Model Index page
*/
  get_template_part('header');
?>
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

        if( get_option( 'page_on_front' ) ) {
          print_r('
            <section>
              <div class="entry-content">
                <h1 class="hide">'.apply_filters( 'the_title', get_post( get_option( 'page_on_front' ) )->post_title ).'</h1>
                '.apply_filters( 'the_content', get_post( get_option( 'page_on_front' ) )->post_content ).'
              </div>
            </section>
          ');
        }
  		?>
    </div>
  </div>
  <div class="row">
    <div class="col col-sm-12">
      <main id="main" class="site-main index" role="main">
        <?php


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
                 'orderby'   =>  array( 'project_year' => 'DESC' ),
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
            <section>
              <h2><?php echo $tax->name; ?></h2>
                  <?php
                   $count = 0;
                   foreach(get_posts($args) as $post) :
                     $count = $count+1;
                     if($count <= 3){
                       get_template_part('template-parts/content', 'projects');
                     }
                   endforeach;?>
            </section>
            <?php

             endif;
         endforeach;
          /// Bof - while custom post type
          // $projects_query = array(
          //   'post_type' => 'projet',
          //   'posts_per_page' => '3',
          //   'order' => 'DESC',
          //   'orderby'   =>  array( 'category' => 'DESC' ),
          // 	//'orderby'   =>  array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
          // 	'meta_key'  => 'project_year'
          // );
          //
          // query_posts($projects_query);
          // while ( have_posts() ) : the_post();
          //     get_template_part('template-parts/content', 'projects');
          // endwhile;
          //// Eof - while custom post type
          wp_reset_query();
        ?>
      </main>
    </div>
  </div>
</div>


<?php
  get_template_part('footer');
?>
