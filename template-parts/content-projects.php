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

        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {
                ?>
                <li><?php echo wp_get_attachment_image( $attachment->ID, 'full' ); ?>
                    <p><?php echo apply_filters( 'the_title', $attachment->post_title ); ?></p>
                </li>
                <?php
            }
        }
        // if ( has_post_thumbnail( $_post->ID ) ) {
        //     echo '<a href="' . get_permalink( $_post->ID ) . '" title="' . esc_attr( $_post->post_title ) . '">';
        //     echo get_the_post_thumbnail( $_post->ID, 'thumbnail' );
        //     echo '</a>';
        // }

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
            print_r($eleve.'<br/>');
            the_title( '<a class="text-primary" href="'.$url_pageProjet.'" title="projet"><span class="text-primary">', '</span></a>' );
          ?>
        </div>
      </div>
      <div class="col col-sm-8">
        <div class="entry-content">
          <?php
            //the_title( '<a class="text-primary" href="'.$url_pageProjet.'" title="projet"><span class="text-primary">', '</span></a>' );
            print('
              <div class="thumb-projet img-responsive" >
                <a class="text-primary" href="'.$url_pageProjet.'" title="projet">
                  <img src="'.$imgURL.'" alt="Projet de '.$eleve.'" />
                </a>
              </div>
            ');
          ?>
        </div>
      </div>
    </div>

  </article>
