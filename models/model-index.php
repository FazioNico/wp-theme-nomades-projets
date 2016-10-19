<?php
/*
	Template Name: Model Index page
*/
  get_template_part('header');
?>
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
        
        if( get_option( 'page_on_front' ) ) {
          print_r('
            <article>
              <div class="entry-content">'.
                  apply_filters( 'the_content', get_post( get_option( 'page_on_front' ) )->post_content )
              .'
              </div>
            </article>
          ');
        }
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
              get_template_part('template-parts/content', 'projects');
          endwhile;
          //// Eof - while custom post type
        ?>
      </main>
    </div>
  </div>
</div>


<?php
  get_template_part('footer');
?>
