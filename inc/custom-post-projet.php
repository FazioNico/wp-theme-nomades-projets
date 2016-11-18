<?php
/**
 * Nomades Projets functions and definitions.
 * for Custom Post Type 'Projet'
 *
 * @package Nomades_Projets
 */



/// Bof - AJOUTER PAGE PROJET (WP_CUSTOM_TYPE) SUR ADMIN
function projet_module() {
	 $args = array(
	 'label' => __('Projets'),
	 'singular_label' => __('Projet'),
	 'public' => true,
	 'show_ui' => true,
	 '_builtin' => false, // It's a custom post type, not built in
	 '_edit_link' => 'post.php?post=%d',
	 'capability_type' => 'post',
	 'hierarchical' => false,
	 'rewrite' => array("slug" => "projets"),
	 'query_var' => "projets", // This goes to the WP_Query schema
	 'supports' => array('title', 'page-attributes'), //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt', 'page-attributes'
	 'taxonomies' => array('category')
	 );
	 register_post_type( 'projet' , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
	 //register_taxonomy_for_object_type('post_tag', 'projet','show_tagcloud=1&hierarchical=false'); // ajout des mots clés pour notre custom post type

	 add_action( 'wp_ajax_delete_attachment', 'delete_attachment' );
	 add_action('save_post','save_attachement',1,2); // used  for single/multiple file upload
	 add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );
	 add_action("admin_init", "admin_init"); //function pour ajouter des champs personnalisés
 	 add_action('save_post', 'save_project'); //function pour la sauvegarde de nos champs personnalisés
}
add_action('init', 'projet_module');

$labelsEleves = array(
	'name' => _x( 'Élèves', 'post type general name' ),
	'singular_name' => _x( 'Élève', 'post type singular name' ),
	'add_new' => _x( 'Add New', 'élève' ),
	'add_new_item' => __( 'Ajouter un élève' ),
	'edit_item' => __( 'Modifier le élève' ),
	'new_item' => __( 'Nouvel élève' ),
	'view_item' => __( "Voir l'élève" ),
	'search_items' => __( 'Rechercher des élèves' ),
	'not_found' =>  __( 'Aucun élève trouvé' ),
	'not_found_in_trash' => __( 'Aucun élève trouvé' ),
	'parent_item_colon' => ''
);
register_taxonomy("eleves", array("projet"), array("hierarchical" => true, "labels" => $labelsEleves, "rewrite" => true));

function field_project_year(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$project_year = $custom["project_year"][0];
	echo('<select class="" name="project_year">');
	for ($i= date("Y") - (date("Y")-2000);  $i <= date("Y")+1; $i++) {
		?>
		<option value="<?php echo $i;?>"  <?php if(intval($project_year) == $i){echo ' selected ';}elseif($i == intval(date("Y")) && $project_year === NULL){echo ' selected ';}?> >
			<?php echo $i;?>
		</option>
		<?php
	}
	echo('</select>');
}

function field_project_type(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$project_type = $custom["project_type"][0];
	?>
	<p>
		<input type="radio" name="project_type" <?php if($project_type === 'wp'){echo 'checked';}?> value="wp">WordPress<br />
		<input type="radio" name="project_type" <?php if($project_type === 'static'){echo 'checked';}?> value="static">Static<br />
		<input type="radio" name="project_type" <?php if($project_type === 'atypique'){echo 'checked';}?> value="atypique">Atypique
	</p>
	<div id="controle-project_type">

	</div>
	<?php
}

function field_project_render_image_attachment_box($post) {
		global $post;

    $attachments = get_posts( array(
      'post_type'      => 'attachment',
      'posts_per_page' => 500,
      'post_status'    => 'any',
      'post_parent'    => $post->ID
    ) );
    //var_dump($attachments);

    if ( $attachments ) {
				echo '<div id="attachementImg">';
        foreach ( $attachments as $attachment ) {
								//$existing_image_id = get_post_meta($post->ID,'_xxxx_attached_image', true);
		            $arr_existing_image = wp_get_attachment_image_src($attachment->ID, 'large');
		            $existing_image_url = $arr_existing_image[0];
		            echo '<img data-id="'.$attachment->ID.'" style="width:100%;max-width:200px;" src="' . $existing_image_url . '" />';
        }
				echo '</div>';
    }
		// exit();
		// /**
		// *
		// * Userd for only one upload by time. it ecrase value of the custom post by the new_item
		// * this way is unable to save multiple attachment files to a custom post
		// *
		// **/
		//
    // // See if there's an existing image. (We're associating images with posts by saving the image's 'attachment id' as a post meta value)
    // // Incidentally, this is also how you'd find any uploaded files for display on the frontend.
		// $testAttachements = get_post_meta($post->ID,'_file_upload');
		// print_r($testAttachements);
    // $existing_image_id = get_post_meta($post->ID,'_xxxx_attached_image', true);
    // if(is_numeric($existing_image_id)) {
    //     echo '<div id="attachementImg">';
    //         $arr_existing_image = wp_get_attachment_image_src($existing_image_id, 'large');
    //         $existing_image_url = $arr_existing_image[0];
    //         echo '<img data-id="'.$existing_image_id.'" style="width:100%;" src="' . $existing_image_url . '" />';
    //     echo '</div>';
    // }
    // // If there is an existing image, show it
    // if($existing_image_id) {
    //   //  echo '<div>Attached Image ID: ' . $existing_image_id . '</div>';
    // }
		//echo 'Upload an image: <input type="file" name="upload_attachment" id="upload_attachment" multiple="multiple"/>';
		// /**
		// *
		// * Eof - Simple uploader file
		// **/


		echo 'Upload an image: <input type="file" name="file_upload[]" id="file_upload" multiple="multiple"/>';
    // See if there's a status message to display (we're using this to show errors during the upload process, though we should probably be using the WP_error class)
    $status_message = get_post_meta($post->ID,'_xxxx_attached_image_upload_feedback', true);
    // Show an error message if there is one
    if($status_message) {
        echo '<div class="upload_status_message">';
            echo $status_message;
        echo '</div>';
    }
    // Put in a hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    echo '<input type="hidden" name="xxxx_manual_save_flag" value="true" />';
}

function field_mention(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$mention = $custom["mention"][0];
	?>
	<p>
		<input type="radio" name="mention" <?php if($mention === '0'){echo 'checked';}?> value="0">Travaux non certifiés<br />
		<input type="radio" name="mention" <?php if($mention === '1'){echo 'checked';}?> value="1">Certifiés<br />
		<input type="radio" name="mention" <?php if($mention === '2'){echo 'checked';}?> value="2">Bien<br />
		<input type="radio" name="mention" <?php if($mention === '3'){echo 'checked';}?> value="3">Très Bien
	</p>
	<?php
}

function field_url_projet(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$url_projet = $custom["url_projet"][0];
	?>
	<input size="50" type="text" value="<?php echo $url_projet;?>" name="url_projet"/>
	<?php
}

// Enable multipart/form-data to upload img form custom type fiel
function post_edit_form_tag( ) {
   echo ' enctype="multipart/form-data"';
}

function delete_attachment( $post ) {
		global $post;
    //echo $_POST['att_ID'];
    $msg = 'Attachment ID [' . $_POST['att_ID'] . '] has been deleted!';
    if( wp_delete_attachment( $_POST['att_ID'], true )) {
        echo $msg;
    }
}

function save_attachement(){
	global $post;
	if ( $_FILES ) {
    $files = $_FILES["file_upload"];
    foreach ($files['name'] as $key => $value) {
      if ($files['name'][$key]) {
        $file = array(
            'name' => $files['name'][$key],
            'type' => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'error' => $files['error'][$key],
            'size' => $files['size'][$key]
        );
        $_FILES = array ("file_upload" => $file);
				//print_r($file);
				save_img_attachement_post($_FILES,$post->ID);
      }
    }
	}
}

function save_img_attachement_post($file,$postID) {
	global $post;
	$post_id = $post->ID;
	//var_dump($_FILES['upload_attachment']);
  $post_type = $post->post_type;
  if($post_id) {
      // Logic to handle specific post types
      switch($post_type) {
          case 'projet':
					//print_r($file);
					//die();
              // HANDLE THE FILE UPLOAD
              // If the upload field has a file in it
              if(isset($file["file_upload"]) && ($file["file_upload"]['size'] > 0)) {
                  // Get the type of the uploaded file. This is returned as "type/extension"
                  $arr_file_type = wp_check_filetype(basename($file["file_upload"]['name']));
                  $uploaded_file_type = $arr_file_type['type'];
                  // Set an array containing a list of acceptable formats
                  $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
                  // If the uploaded file is the right format
                  if(in_array($uploaded_file_type, $allowed_file_types)) {
										require_once(ABSPATH . "wp-admin" . '/includes/image.php');
										require_once(ABSPATH . "wp-admin" . '/includes/file.php');
										require_once(ABSPATH . "wp-admin" . '/includes/media.php');

										$attach_id = media_handle_upload( 'file_upload', $post_id );
										if ( is_numeric( $attach_id ) ) {
												set_post_thumbnail($post_id, $attach_id);
												//update_post_meta( $post_id, '_xxxx_attached_image', $attach_id );
												$upload_feedback = false;
										}

											// /**
											// *
											// * Userd for only one upload by time. it ecrase value of the custom post by the new_item
											// * this way is unable to save multiple attachment files to a custom post
											// *
											// **/
                      // // Options array for the wp_handle_upload function. 'test_upload' => false
                      // $upload_overrides = array( 'test_form' => false );
                      // // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
                      // $uploaded_file = wp_handle_upload($file, $upload_overrides);
                      // // If the wp_handle_upload call returned a local path for the image
                      // if(isset($uploaded_file['file'])) {
                      //     // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
                      //     $file_name_and_location = $uploaded_file['file'];
                      //     // Generate a title for the image that'll be used in the media library
                      //     $file_title_for_media_library = 'your title here';
                      //     // Set up options array to add this file as an attachment
                      //     $attachment = array(
                      //         'post_mime_type' => $uploaded_file_type,
                      //         'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
                      //         'post_content' => '',
                      //         'post_status' => 'inherit'
                      //     );
                      //     // Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
                      //     $attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
                      //     require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                      //     $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                      //     wp_update_attachment_metadata($attach_id,  $attach_data);
                      //     // Before we update the post meta, trash any previously uploaded image for this post.
                      //     // You might not want this behavior, depending on how you're using the uploaded images.
                      //     // $existing_uploaded_image = (int) get_post_meta($post_id,'_xxxx_attached_image', true);
                      //     // if(is_numeric($existing_uploaded_image)) {
                      //     //     wp_delete_attachment($existing_uploaded_image);
                      //     // }
                      //     // Now, update the post meta to associate the new image with the post
                      //     update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
                      //     // Set the feedback flag to false, since the upload was successful
                      //     $upload_feedback = false;
                      // } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
                      //     $upload_feedback = 'There was a problem with your upload.';
                      //     update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
                      // }
											// /**
											// *
											// * Eof - Simple uploader file
											// **/

                  } else { // wrong file type
                      $upload_feedback = 'Please upload only image files (jpg, gif or png).';
                      update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
                  }
              } else { // No file was passed
                  $upload_feedback = false;
              }
              // Update the post meta with any feedback
              update_post_meta($post_id,'_xxxx_attached_image_upload_feedback',$upload_feedback);
          break;
          default:
      } // End switch
  	return;
	} // End if manual save flag
	return;
}

function admin_init(){ //initialisation des champs spécifiques
	 wp_enqueue_script( 'gulp-javascript', get_template_directory_uri() . '/js/app.min.js', array(), true );
	 add_meta_box("field_url_projet", "Url du projet", "field_url_projet", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction url_projet()
	 add_meta_box("field_project_type", "Type de Projet", "field_project_type", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()

	 // img reader & saver
	 add_meta_box('field_project_render_image_attachment_box', 'Upload Image', 'field_project_render_image_attachment_box', 'projet', 'normal', 'high');
	 //add_meta_box("displayAttachedImg", "Images du projet", "displayAttachedImg", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction custom_thumbnail()

	 add_meta_box("field_mention", "Mentions", "field_mention", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()
	 add_meta_box("field_project_year", "Année", "field_project_year", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()
}

function save_project(){
		//sauvegarde des champs spécifiques
		global $post;
		update_post_meta($post->ID, "project_year", $_POST["project_year"]);
		update_post_meta($post->ID, "project_type", $_POST["project_type"]);
		update_post_meta($post->ID, "mention", $_POST["mention"]);
		update_post_meta($post->ID, "url_projet", $_POST["url_projet"]); //enregistrement dans la base de données
		return;
}





/*
function insert_attachment($file_handler,$post_id,$setthumb='false') {
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
  $attach_id = media_handle_upload( $file_handler, $post_id );
  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;
}
global $post;
if ( $_FILES ) {
	$files = $_FILES['upload_attachment'];
	foreach ($files['name'] as $key => $value) {
		if ($files['name'][$key]) {
			$file = array(
				'name'     => $files['name'][$key],
				'type'     => $files['type'][$key],
				'tmp_name' => $files['tmp_name'][$key],
				'error'    => $files['error'][$key],
				'size'     => $files['size'][$key]
			);
			$_FILES = array("upload_attachment" => $file);
			foreach ($_FILES as $file => $array) {
				$newupload = insert_attachment($file,$post->ID);
			}
		}
	}
}
*/
