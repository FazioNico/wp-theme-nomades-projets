<?php
/*

	Template Name: Model Index page

*/
  get_template_part('header');

  //// Bof - Menu side
  wp_nav_menu(array(
    'theme_location' => 'secondary',
    'container_class' => 'new_menu_class'
  ));
  //// Eof - Menu side


  /// Bof - while custom post type
  $projects_query = array(
    'post_type' => 'projet',
    'posts_per_page' => '20',
    'orderby' => 'menu_order',
    'order' => 'ASC',
  	/*
    'meta_query' => array(
  		array(
  			'key'       => 'project_type',
  			'value'     => 'static',
  		),
  	)
    */

  );
  query_posts($projects_query);
  while ( have_posts() ) : the_post();
      the_title( '<h1 class="entry-title">', '</h1>' );
      // get the custome meta post ( echo $customMetaPost["url_projet"][0];)
      $customMetaPost = get_post_custom($post->ID);
      $mention = $customMetaPost["mention"][0];
      $project_year = $customMetaPost["project_year"][0];
      $eleve = get_the_terms($post->ID, 'eleves', '', ', ','')[0]->name;
      //$url_projet = $customMetaPost["url_projet"][0];
      var_dump($mention);
      var_dump($project_year);
      var_dump($eleve);

  endwhile;
  //// Eof - while custom post type

  get_template_part('footer');
 ?>
