<?php
/**
 * PHILHARMONIA functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package PHILHARMONIA
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( get_template_directory() . '/vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}


if ( ! defined( 'PH_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'PH_VERSION', '1.0.0' );
}

if ( ! function_exists( 'philharmonia_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function philharmonia_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on PHILHARMONIA, use a find and replace
		 * to change 'philharmonia' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'philharmonia', get_template_directory() . '/languages' );

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
		register_nav_menus(
			array(
				'main_menu' => esc_html__( 'Main menu', 'philharmonia' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'philharmonia_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'philharmonia_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function philharmonia_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header logo', 'philharmonia' ),
			'id'            => 'header-logo',
			'description'   => esc_html__( 'Add widgets here.', 'philharmonia' ),
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header right sidebar', 'philharmonia' ),
			'id'            => 'header-right-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'philharmonia' ),
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer sidebar', 'philharmonia' ),
			'id'            => 'footer-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'philharmonia' ),
            'before_widget' => '',
            'after_widget'  => '',
		)
	);

}
add_action( 'widgets_init', 'philharmonia_widgets_init' );

add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_sub_page') ) {

        // Add parent.
        $parent = acf_add_options_page();

        // Add sub page.
        $child = acf_add_options_sub_page('Header');
    }
}

/**
 * Enqueue scripts and styles.
 */
function philharmonia_scripts() {
    wp_enqueue_style( 'philharmonia-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), PH_VERSION );
    wp_enqueue_style( 'philharmonia-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), PH_VERSION );
    wp_enqueue_style( 'philharmonia-owl-carousel', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), PH_VERSION );
    wp_enqueue_style( 'philharmonia-animate', get_template_directory_uri() . '/css/animate.css', array(), PH_VERSION );
    wp_enqueue_style( 'philharmonia-stylesheet', get_template_directory_uri() . '/css/stylesheet.css', array(), PH_VERSION );
	wp_enqueue_style( 'philharmonia-style', get_stylesheet_uri(), array(), PH_VERSION );

	wp_enqueue_script( 'philharmonia-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), PH_VERSION, true );
	wp_enqueue_script( 'philharmonia-owl-carousel-js', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), PH_VERSION, true );
	wp_enqueue_script( 'philharmonia-h5validate-js', get_template_directory_uri() . '/js/jquery.h5validate.js', array(), PH_VERSION, true );
	wp_enqueue_script( 'philharmonia-main-js', get_template_directory_uri() . '/js/main.js', array(), PH_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'philharmonia_scripts' );

require get_template_directory() . '/inc/widgets/HeaderWidgetLogo.php';
require get_template_directory() . '/inc/widgets/HeaderWidgetRight.php';

//Carbon_Fields
add_action( 'carbon_fields_register_fields', 'crb_attach_carbon_fields' );
function crb_attach_carbon_fields() {
    require get_template_directory() . '/inc/custom-fields/fields.php';
}

add_filter( 'nav_menu_css_class', 'change_menu_item_css_classes', 10, 4 );
function change_menu_item_css_classes( $classes, $item, $args, $depth ) {
    if( $args->theme_location === 'main_menu' && in_array('menu-item-has-children', $classes )  ){
        $classes[] = 'dropdown';
    }
    return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'change_wp_nav_menu', 10, 3 );
function change_wp_nav_menu( $classes, $args, $depth ) {
    foreach ( $classes as $key => $class ) {
        if ( $class == 'sub-menu' ) {
            $classes[$key] = 'dropdown-menu';
        }
    }
    return $classes;
}


if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
endif;

