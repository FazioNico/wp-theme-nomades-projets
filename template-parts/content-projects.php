<?php
/**
 * Template part for displaying loop post
 * Only use with custome wpQuery:  post_type: projet
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */

?>
<?php
  //print_r($post);
  // the_title( '<h1 class="entry-title">', '</h1>' );
  // get the custome meta post ( echo $customMetaPost["url_projet"][0];)
  // $existing_image_id = get_post_meta($post->ID,'_project_attached_image', true);
  // if(is_numeric($existing_image_id)) {
  //     echo '<div>';
  //         $arr_existing_image = wp_get_attachment_image_src($existing_image_id, 'large');
  //         $existing_image_url = $arr_existing_image[0];
  //         echo '<img style="width:100%" src="' . $existing_image_url . '" />';
  //     echo '</div>';
  // }
  //
//var_dump( $post);


  $attachments = get_posts( array(
    'post_type'      => 'attachment',
    'posts_per_page' => 500,
    'post_status'    => 'any',
    'post_parent'    => $post->ID
  ) );
  //var_dump($attachments);

  $customMetaPost = get_post_custom($post->ID);
  $mention = $customMetaPost["mention"][0];
  switch ($mention) {
    case 0:
      $mentionTitle = 'Travaux non certifiés';
      break;
    case 1:
      $mentionTitle = 'Certification';
      break;
    case 2:
      $mentionTitle = 'Mention Bien';
      break;
    case 3:
      $mentionTitle = 'Mention Très Bien';
      break;
  }
  $project_year = $customMetaPost["project_year"][0];
  $eleve = get_the_terms($post->ID, 'eleves', '', ', ','')[0]->name;
  $url_projet = $customMetaPost["url_projet"][0];
  $url_pageProjet = esc_url( get_permalink() );
  $imgURL = 'https://placeholdit.imgix.net/~text?txtsize=33&txt=300%C3%97150&w=300&h=150';
  //var_dump($mention);
  //var_dump($project_year);
  //var_dump($eleve);

  ?>
  <article class="">
    <div class="row">
      <div class="col col-sm-4">
        <div class="entry-content">
          <?php
            (!empty($mentionTitle) ? print_r('<span class="lighten-text">'.$mentionTitle.'</span><br/>') : '');
            (!empty($eleve) ? print_r('<span class="darken-text">'.$eleve.'</span><br/>') : '');
            (!empty($url_pageProjet) ? the_title( '<a class="text-primary" href="'.$url_pageProjet.'" title="projet"><span class="text-primary">', '</span></a>' ) : '');
          ?>
        </div>
      </div>
      <div class="col col-sm-8">
        <div class="entry-content">
          <div class="attachments row">
            <?php
              $counter = 0;
              if ( $attachments ) {
                foreach ( $attachments as $attachment ) {
                  ++$counter;
                  if($counter <= 3){
                    print('
                      <div class="thumb-projet col col-sm-4" >
                        <a class="text-primary" href="'.$url_pageProjet.'" title="projet">
                          <img class="img-responsive" src="'.wp_get_attachment_image_src( $attachment->ID, 'full' )[0].'" alt="Projet de '.$eleve.'" />
                        </a>
                      </div>
                    ');
                  }
                }
              }
              //the_title( '<a class="text-primary" href="'.$url_pageProjet.'" title="projet"><span class="text-primary">', '</span></a>' );
            ?>
          </div><!--  Eof .attachments -->
        </div><!--  Eof .entry-content -->

      </div><!--  Eof .col-sm-8 -->
    </div><!--  Eof .row -->
  </article>
