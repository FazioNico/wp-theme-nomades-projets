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
      <main id="main" class="site-main index" role="main">
        <?php
          /// Bof - while custom post type
          $projects_query = array(
            'post_type' => 'projet',
            'posts_per_page' => '20',
            'orderby' => 'menu_order',
            'order' => 'ASC'
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
