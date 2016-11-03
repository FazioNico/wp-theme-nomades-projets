<?php
add_action( 'wp_ajax_nopriv_copy_distant_folder', 'copy_distant_folder' );
add_action( 'wp_ajax_copy_distant_folder', 'copy_distant_folder' );

function copy_distant_folder() {
  global $post;
  echo($_POST['params']);
	// $love = get_post_meta( $_POST['post_id'], 'post_love', true );
	// $love++;
	// if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	// 	update_post_meta( $_POST['post_id'], 'post_love', $love );
	// 	echo $love;
	// }
	die();
}
 ?>
