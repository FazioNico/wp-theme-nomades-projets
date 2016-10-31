<?php
/**
 * Nomades Projets functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Nomades_Projets
 */

if ( ! function_exists( 'nomades_projets_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nomades_projets_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Nomades Projets, use a find and replace
	 * to change 'nomades-projets' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'nomades-projets', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'nomades-projets' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'nomades_projets_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'nomades_projets_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function nomades_projets_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nomades_projets_content_width', 640 );
}
add_action( 'after_setup_theme', 'nomades_projets_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function nomades_projets_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'nomades-projets' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'nomades-projets' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'nomades_projets_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function nomades_projets_scripts() {
	////// from Gulp task
	wp_enqueue_style( 'gulp-style', get_stylesheet_uri() );
	wp_enqueue_script( 'gulp-jsDepencencies', get_template_directory_uri() . '/js/bundle.min.js', array(), '20151215', true );
	wp_enqueue_script( 'gulp-javascript', get_template_directory_uri() . '/js/app.min.js', array(), '20151215', true );
	/////
	//wp_enqueue_script( 'nomades-projets-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	//wp_enqueue_script( 'nomades-projets-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		//wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nomades_projets_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/** ----------------------------------------------------------------------- */
//// Bof - Add menu location
function register_my_menus() {
  register_nav_menus(
    array(
      'secondary' => __( 'Secondary' )
    )
  );
}
add_action( 'init', 'register_my_menus' );
//// Eof - add menu location
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
	 'supports' => array('title', 'thumbnail', 'page-attributes'), //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt', 'page-attributes'
	 'taxonomies' => array('category')
	 );
	 register_post_type( 'projet' , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
	 //register_taxonomy_for_object_type('post_tag', 'projet','show_tagcloud=1&hierarchical=true'); // ajout des mots clés pour notre custom post type
 	 add_action("admin_init", "admin_init"); //function pour ajouter des champs personnalisés
 	 add_action('save_post', 'save_custom'); //function pour la sauvegarde de nos champs personnalisés
}
add_action('init', 'projet_module');

function admin_init(){ //initialisation des champs spécifiques
	 wp_enqueue_script( 'gulp-javascript', get_template_directory_uri() . '/js/app.min.js', array(), true );
	 add_meta_box("url_projet", "Url du projet", "url_projet", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction url_projet()
	 add_meta_box("project_type", "Type de Projet", "project_type", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()

	 // img reader & saver
	 add_meta_box('project_image_box', 'Upload Image', 'project_render_image_attachment_box', 'projet', 'normal', 'high');
	 //add_meta_box("custom_thumbnail", "Images Preview", "custom_thumbnail", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction custom_thumbnail()
	 //add_meta_box("displayAttachedImg", "Images du projet", "displayAttachedImg", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction custom_thumbnail()

	 add_meta_box("mention", "Mentions", "mention", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()
	 add_meta_box("project_year", "Année", "project_year", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction mention()
}
function project_year(){     //La fonction qui affiche notre champs personnalisé dans l'administration
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
function project_type(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$project_type = $custom["project_type"][0];
	?>
	<p>
		<input type="radio" name="project_type" <?php if($project_type === 'wp'){echo 'checked';}?> value="wp">WordPress<br />
		<input type="radio" name="project_type" <?php if($project_type === 'static'){echo 'checked';}?> value="static">Static<br />
		<input type="radio" name="project_type" <?php if($project_type === 'atypique'){echo 'checked';}?> value="atypique">Atypique
	</p>
	<?php
}
///// Creat custom fiels for thumbnail project
// Enable multipart/form-data to upload img form custom type fiel
function post_edit_form_tag( ) {
   echo ' enctype="multipart/form-data"';
}
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );

function displayAttachedImg(){
	global $post;
	echo 'Ajouter une image<br/>';
	echo '<input type="file" name="upload_attachment" id="upload_attachment" value="'.get_option('upload_attachment').'"/>';
	echo '<hr/>';
	$attachments = get_posts( array(
      'post_type'   => 'attachment',
      'numberposts' => -1,
      'post_status' => null,
      'post_parent' => $post->ID
  ) );
	echo '<div id="attachementImg">';
	var_dump($post->ID);
  if ( $attachments ) {
      foreach ( $attachments as $attachment ) {
				//var_dump(wp_get_attachment_image_src( $attachment->ID, 'full' ));
          ?>
							<img data-id="<?php echo $attachment->ID;?>" alt="img ID: <?php echo $attachment->ID;?>" src="<?php echo wp_get_attachment_image_src( $attachment->ID, 'full' )[0]; ?>" style="width:100%;">
					<?php
      }
  }
	echo '</div>';
}

function delete_attachment( $post ) {
		global $post;
    //echo $_POST['att_ID'];
    $msg = 'Attachment ID [' . $_POST['att_ID'] . '] has been deleted!';
    if( wp_delete_attachment( $_POST['att_ID'], true )) {
        echo $msg;
    }
    die();
}
add_action( 'wp_ajax_delete_attachment', 'delete_attachment' );

function project_render_image_attachment_box($post) {
		global $post;

		    // See if there's an existing image. (We're associating images with posts by saving the image's 'attachment id' as a post meta value)
		    // Incidentally, this is also how you'd find any uploaded files for display on the frontend.
		    $existing_image_id = get_post_meta($post->ID,'_xxxx_attached_image', true);
		    if(is_numeric($existing_image_id)) {

		        echo '<div id="attachementImg">';
		            $arr_existing_image = wp_get_attachment_image_src($existing_image_id, 'large');
		            $existing_image_url = $arr_existing_image[0];
		            echo '<img data-id="'.$existing_image_id.'" style="width:100%;" src="' . $existing_image_url . '" />';
		        echo '</div>';

		    }

		    // If there is an existing image, show it
		    if($existing_image_id) {

		      //  echo '<div>Attached Image ID: ' . $existing_image_id . '</div>';

		    }

		    echo 'Upload an image: <input type="file" name="upload_attachment" id="upload_attachment" />';

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




function custom_thumbnail(){
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	print_r(get_attached_media( 'image', $post->ID ));
	echo '<input type="file" name="upload_attachment" id="upload_attachment" value="'.get_option('upload_attachment').'"/>';
}

function mention(){     //La fonction qui affiche notre champs personnalisé dans l'administration
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
function url_projet(){     //La fonction qui affiche notre champs personnalisé dans l'administration
	global $post;
	$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	$url_projet = $custom["url_projet"][0];
	?>
	<input size="70" type="text" value="<?php echo $url_projet;?>" name="url_projet"/>
	<?php
}

function save_img_attachement_post() {
	global $post;
	$post_id = $post->ID;
	//var_dump($_FILES['upload_attachment']);
    // Get the post type. Since this function will run for ALL post saves (no matter what post type), we need to know this.
    // It's also important to note that the save_post action can runs multiple times on every post save, so you need to check and make sure the
    // post type in the passed object isn't "revision"
    $post_type = $post->post_type;
    // Make sure our flag is in there, otherwise it's an autosave and we should bail.
    if($post_id) {

        // Logic to handle specific post types
        switch($post_type) {
            // If this is a post. You can change this case to reflect your custom post slug
            case 'projet':
                // HANDLE THE FILE UPLOAD
                // If the upload field has a file in it
                if(isset($_FILES['upload_attachment']) && ($_FILES['upload_attachment']['size'] > 0)) {
                    // Get the type of the uploaded file. This is returned as "type/extension"
                    $arr_file_type = wp_check_filetype(basename($_FILES['upload_attachment']['name']));
                    $uploaded_file_type = $arr_file_type['type'];
                    // Set an array containing a list of acceptable formats
                    $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
                    // If the uploaded file is the right format
                    if(in_array($uploaded_file_type, $allowed_file_types)) {
                        // Options array for the wp_handle_upload function. 'test_upload' => false
                        $upload_overrides = array( 'test_form' => false );
                        // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
                        $uploaded_file = wp_handle_upload($_FILES['upload_attachment'], $upload_overrides);
                        // If the wp_handle_upload call returned a local path for the image
                        if(isset($uploaded_file['file'])) {
                            // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
                            $file_name_and_location = $uploaded_file['file'];
                            // Generate a title for the image that'll be used in the media library
                            $file_title_for_media_library = 'your title here';
                            // Set up options array to add this file as an attachment
                            $attachment = array(
                                'post_mime_type' => $uploaded_file_type,
                                'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            // Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
                            $attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
                            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                            wp_update_attachment_metadata($attach_id,  $attach_data);
                            // Before we update the post meta, trash any previously uploaded image for this post.
                            // You might not want this behavior, depending on how you're using the uploaded images.
                            // $existing_uploaded_image = (int) get_post_meta($post_id,'_xxxx_attached_image', true);
                            // if(is_numeric($existing_uploaded_image)) {
                            //     wp_delete_attachment($existing_uploaded_image);
                            // }
                            // Now, update the post meta to associate the new image with the post
                            update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
                            // Set the feedback flag to false, since the upload was successful
                            $upload_feedback = false;
                        } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
                            $upload_feedback = 'There was a problem with your upload.';
                            update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
                        }
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
add_action('save_post','save_img_attachement_post',1,2);

function save_custom(){ //sauvegarde des champs spécifiques
// 	if ( !function_exists( 'wp_handle_upload' ) ) {
// 			require_once( ABSPATH . 'wp-admin/includes/file.php' );
// 	}
	global $post;
// 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { // fonction pour éviter  le vidage des champs personnalisés lors de la sauvegarde automatique
// 		return $postID;
// 	}
	$post_id = $post->ID;
// 	// post type in the passed object isn't "revision"
// 	$post_type = $post->post_type;
//
// 	// Make sure our flag is in there, otherwise it's an autosave and we should bail.
// 	if($post_id) {
// 			// Logic to handle specific post types
//
// 			switch($post_type) {
// 					// If this is a post. You can change this case to reflect your custom post slug
// 					case 'projet':
// 							// HANDLE THE FILE UPLOAD
// 							// If the upload field has a file in it
// 							if(isset($_FILES['upload_attachment']) && ($_FILES['upload_attachment']['size'] > 0)) {
// 									// Get the type of the uploaded file. This is returned as "type/extension"
// 									$arr_file_type = wp_check_filetype(basename($_FILES['upload_attachment']['name']));
// 									$uploaded_file_type = $arr_file_type['type'];
// 									// Set an array containing a list of acceptable formats
// 									$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
// 									// If the uploaded file is the right format
// 									if(in_array($uploaded_file_type, $allowed_file_types)) {
// 											// Options array for the wp_handle_upload function. 'test_upload' => false
// 											$upload_overrides = array( 'test_form' => false );
// 											// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
// 											$uploaded_file = wp_handle_upload($_FILES['upload_attachment'], $upload_overrides);
// 											// If the wp_handle_upload call returned a local path for the image
// 											if(isset($uploaded_file['file'])) {
// 													// The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
// 													$file_name_and_location = $uploaded_file['file'];
// 													// Generate a title for the image that'll be used in the media library
// 													$file_title_for_media_library = '';
// 													// Set up options array to add this file as an attachment
// 													$attachment = array(
// 															'post_mime_type' => $uploaded_file_type,
// 															'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
// 															'post_content' => '',
// 															'post_status' => 'inherit'
// 													);
// 													// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
// 													$attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
// 													require_once(ABSPATH . "wp-admin" . '/includes/image.php');
// 													$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
// 													wp_update_attachment_metadata($attach_id,  $attach_data);
// 													// Before we update the post meta, trash any previously uploaded image for this post.
// 													// You might not want this behavior, depending on how you're using the uploaded images.
//
// 													// $existing_uploaded_image = (int) get_post_meta($post_id,'_project_attached_image', true);
// 													// if(is_numeric($existing_uploaded_image)) {
// 													// 		wp_delete_attachment($existing_uploaded_image);
// 													// }
//
// 													// Now, update the post meta to associate the new image with the post
// 													update_post_meta($post_id,'_project_attached_image',$attach_id);
// 													// Set the feedback flag to false, since the upload was successful
// 													$upload_feedback = false;
// 											} else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
// 													$upload_feedback = 'There was a problem with your upload.';
// 													update_post_meta($post_id,'_project_attached_image',$attach_id);
// 											}
// 									} else { // wrong file type
// 											$upload_feedback = 'Please upload only image files (jpg, gif or png).';
// 											update_post_meta($post_id,'_project_attached_image',$attach_id);
// 									}
// 							} else { // No file was passed
// 									$upload_feedback = false;
// 							}
// 							// Update the post meta with any feedback
// 							update_post_meta($post_id,'_project_attached_image_upload_feedback',$upload_feedback);
// 					break;
// 					default:
// 			} // End switch
// 			return;
// 	} // End if manual save flag
//
// 	//
// 	// // $filename should be the path to a file in the upload directory.
// 	// $filename = $_FILES['upload_attachment']['name'];
// 	// $uploadedfile = $_FILES['upload_attachment'];
// 	// $upload_overrides = array( 'test_form' => false );
// 	// //$upload_overrides = array( 'upload_attachment' => false );
// 	// $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
// 	// if ( $movefile && ! isset( $movefile['error'] ) ) {
// 	//     //print_r( "File is valid, and was successfully uploaded.\n");
// 	//     //var_dump( $movefile );
// 	//
// 	// 		// The ID of the post this attachment is for.
// 	// 		$parent_post_id = $post->ID;
// 	// 		// Check the type of file. We'll use this as the 'post_mime_type'.
// 	// 		$filetype = wp_check_filetype( basename( $filename ), null );
// 	// 		// Get the path to the upload directory.
// 	// 		$wp_upload_dir = wp_upload_dir();
// 	// 		//var_dump($wp_upload_dir);
// 	// 		//die();
// 	// 		// Prepare an array of post data for the attachment.
// 	// 		$attachment = array(
// 	// 			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
// 	// 			'post_mime_type' => $filetype['type'],
// 	// 			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
// 	// 			'post_content'   => '',
// 	// 			'post_status'    => 'inherit'
// 	// 		);
// 	// 		// Insert the attachment.
// 	// 		$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
// 	// 		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
// 	// 		require_once( ABSPATH . 'wp-admin/includes/image.php' );
// 	// 		// Generate the metadata for the attachment, and update the database record.
// 	// 		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
// 	// 		wp_update_attachment_metadata( $attach_id, $attach_data );
// 	// 		set_post_thumbnail( $parent_post_id, $attach_id );
// 	//
// 	// } else {
// 	//     print_r( $movefile."\n");
// 	// 		print_r($uploadedfile);
// 	// 		die();
// 	// }
// 	// update_post_meta($post->ID, "upload_attachment", $_POST["upload_attachment"]);
//
//
	update_post_meta($post->ID, "project_year", $_POST["project_year"]);
	update_post_meta($post->ID, "project_type", $_POST["project_type"]);
	update_post_meta($post->ID, "mention", $_POST["mention"]);
	update_post_meta($post->ID, "url_projet", $_POST["url_projet"]); //enregistrement dans la base de données
	return;
 }

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

/* display all post_type in $WP_Query */
function add_custom_post_type_to_wp_query($query) {
    if(
        empty($query->query['post_type'])
        or $query->query['post_type'] === 'post'
    ){
        $query->set('post_type', array('post_type' => 'any'));
    }
}
add_action('pre_get_posts', 'add_custom_post_type_to_wp_query');
/// Eof - AJOUTER PAGE PROJET (WP_CUSTOM_TYPE) SUR ADMIN

/// Bof - Remove Tags prefix in the_archive_title and more...
add_filter( 'get_the_archive_title', function ($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    }
		elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    }
		elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>' ;
    }
    return $title;
});
/// Eof - Remove Tags prefix




// remove & clean wp header
// Remove All Yoast HTML Comments
if (defined('WPSEO_VERSION')){
    $instance = WPSEO_Frontend::get_instance();
    remove_action( 'wpseo_head', array( $instance, 'debug_marker' ), 2 );
    remove_action( 'wp_head', array( $instance, 'head' ), 1 );
    add_action( 'wp_head', 'custom_yoast_head', 1 );
    function custom_yoast_head() {
        global $wp_query;
        $old_wp_query = null;
        if ( ! $wp_query->is_main_query() ) {
            $old_wp_query = $wp_query;
            wp_reset_query();
        }
        do_action( 'wpseo_head' );
        if ( ! empty( $old_wp_query ) ) {
            $GLOBALS['wp_query'] = $old_wp_query;
            unset( $old_wp_query );
        }
        return;
    }
}
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
add_filter('wpseo_json_ld_output', '__return_true'); // remove application/ld+json from yoast
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 ); // Remove the REST API lines from the HTML Header
//remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 ); // Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action( 'rest_api_init', 'wp_oembed_register_route' ); // Remove the REST API endpoint.
add_filter( 'embed_oembed_discover', '__return_false' ); // Turn off oEmbed auto discovery.
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 ); // Don't filter oEmbed results.
//add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' ); // Remove all embeds rewrite rules.
add_filter('login_errors',create_function('$a', "return null;")); // remove login error display










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
