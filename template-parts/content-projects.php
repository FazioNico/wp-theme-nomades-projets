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
  // the_title( '<h1 class="entry-title">', '</h1>' );
  // get the custome meta post ( echo $customMetaPost["url_projet"][0];)
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
    <div class="entry-content">
      <?php
        the_title( '<a class="text-primary" href="'.$url_pageProjet.'" title="projet"><span class="text-primary">', '</span></a>' );
        print('
          <div class="thumb-projet img-responsive" >
            <a class="text-primary" href="'.$url_pageProjet.'" title="projet">
              <img src="'.$imgURL.'" alt="Projet de '.$eleve.'" />
            </a>
          </div>
        ');
      ?>
    </div>

  </article>
