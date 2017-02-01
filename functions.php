<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   28-09-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 01-02-2017

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
	wp_enqueue_script( 'gulp-jsDepencencies', get_template_directory_uri() . '/js/bundle.min.js', array(), time(), true );
	wp_enqueue_script( 'gulp-javascript', get_template_directory_uri() . '/js/app.min.js', array(), time(), true );
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

/**
 * Load CustomPost Projet.
 */
require get_template_directory() . '/inc/custom-post-projet.php';
/** ----------------------------------------------------------------------- */

/**
 * Load Ajax WP call action.
 */
require get_template_directory() . '/inc/ajax-wp-call-action.php';
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
