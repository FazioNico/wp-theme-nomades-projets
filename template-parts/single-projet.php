<?php
/**
 * Template part for displaying PROJET IN SINGLE PAGE.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nomades_Projets
 */
 $customMetaPost = get_post_custom($post->ID);
 $mention = $customMetaPost["mention"][0];
 $project_year = $customMetaPost["project_year"][0];
 $eleve = get_the_terms($post->ID, 'eleves', '', ', ','')[0]->name;
 $url_projet = $customMetaPost["url_projet"][0];
 $url_pageProjet = esc_url( get_permalink() );
 $imgURL = 'https://placeholdit.imgix.net/~text?txtsize=33&txt=300%C3%97150&w=300&h=150';

 //echo('Single: single-projet.php');
?>
<article class="">
	<header class="entry-header">
			<h1 class="entry-title"><?php print($eleve);?></h1>
	</header>
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
