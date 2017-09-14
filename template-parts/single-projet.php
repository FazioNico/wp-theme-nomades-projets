<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   05-10-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 14-09-2017

/**
 * Template part for displaying PROJET IN SINGLE PAGE.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */
 $customMetaPost = get_post_custom($post->ID);
 $mention = $customMetaPost["mention"][0];
 $type = $customMetaPost["project_type"][0];
 if($type === 'atypique'){
   $url_pageProjet = $customMetaPost['url_projet'][0]; //esc_url( get_permalink() );
 }
 else {
   $url_pageProjet = '../../../../'.$customMetaPost['url_projet'][0]; //esc_url( get_permalink() );
 }
 $project_year = $customMetaPost["project_year"][0];
 $eleve = get_the_terms($post->ID, 'eleves', '', ', ','')[0]->name;
 $defaultImgURL = 'https://placeholdit.imgix.net/~text?txtsize=33&txt=300%C3%97150&w=300&h=150';
 switch ($mention) {
   case 0:
     $mentionTitle = 'Projet non certifié';
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
 //echo('Single: single-projet.php');
?>
<article class="projet-eleve-page">
	<header class="entry-header">
      <?php
        the_title( '<h1><span class="text-primary">', '</span></h1>' );
      ?>
      <p><?php  echo $mentionTitle;?></p>
			<p><?php print($eleve);?></p>
      <p><?php (!empty($project_year) ? print_r('<span class="lighten-text">'.$project_year.'</span>') : '');?></p>
	</header>
	<div class="entry-content">
		<?php
      //echo ('<a class="text-primary" href="'.$url_pageProjet.'" title="projet">voir le projet</a>');
			$images = get_attached_media( 'image', $post->ID );
      if(count($images)<=0){
        print('
          <div class="thumb-projet img-responsive" >
            <a class="text-primary" href="'.$url_pageProjet.'" title="'.get_the_title().'" target="_blank">
              <img src="'.$defaultImgURL.'" class="responsive-img" alt="Projet de '.$eleve.'" />
            </a>
          </div>
        ');
      }
      foreach($images as $image) {
          //echo '<img src="'.wp_get_attachment_image_src($image->ID,'full')[0].'" />';
          print('
    				<div class="thumb-projet img-responsive" >
    					<a class="text-primary" href="'.$url_pageProjet.'" title="'.get_the_title().'" target="_blank">
    						<img src="'.wp_get_attachment_image_src($image->ID,'full')[0].'" class="responsive-img" alt="Projet de '.$eleve.'" />
    					</a>
    				</div>
    			');
      }

		?>
	</div>

</article>
