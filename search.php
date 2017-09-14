<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   28-09-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 14-09-2017

/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Nomades_Projets
 */

get_header(); ?>
<div class="conatainer hide">

  <div class="row">
    <div class="col col-sm-12">
      <main id="main" class="site-main archive" role="main">
          <h1>Resultats de la recherche</h1>
      		<?php


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
          ?>

      </main><!-- #main -->
    </div>
  </div>
</div>


<?php
//get_sidebar();
get_footer();
