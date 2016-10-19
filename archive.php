<?php
/**
 * The template for displaying archive pages.
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
      		if ( have_posts() ) :
      			/* Start the Loop */
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
      			the_posts_navigation();
      		else :
      			get_template_part( 'template-parts/content', 'none' );
      		endif;
          ?>

      </main><!-- #main -->
    </div>
  </div>
</div>


<?php
//get_sidebar();
get_footer();
