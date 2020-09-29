<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * The Affiliate Links Core Plugin Class.
 */
class Affiliate_Links {

	public static $plugin_name = 'Affiliate Links';
	public static $singular_name = 'Affiliate Link';
	public static $plugin_slug = 'affiliate-links';
	public static $post_type = 'affiliate-links';
	public static $taxonomy = 'affiliate-links-cat';
	public static $slug = 'go';
	public static $settings = FALSE;

	public function __construct() {
		add_action( 'init', array( $this, 'register_affiliate_links' ) );
		add_action( 'init', array( $this, 'register_affiliate_links_cat' ) );
		add_action( 'after_wp_tiny_mce', array(
			$this,
			'get_button_settings_template',
		) );

		self::$settings = get_option( 'affiliate_links_settings' );

		add_action( 'template_redirect', array( $this, 'redirect' ) );

		require_once AFFILIATE_LINKS_PLUGIN_DIR . 'admin/class-affiliate-links-settings.php';

		new Affiliate_Links_Settings();

		if ( is_admin() ) {
			require_once AFFILIATE_LINKS_PLUGIN_DIR . 'admin/class-affiliate-links-metabox.php';
			require_once AFFILIATE_LINKS_PLUGIN_DIR . 'admin/class-affiliate-links-buttons.php';
		}

		require_once AFFILIATE_LINKS_PLUGIN_DIR . 'includes/class-affiliate-links-shortcode.php';

		if ( ! empty( self::$settings['slug'] ) ) {
			self::$slug = self::$settings['slug'];
		}

		if ( isset( self::$settings['category'] ) ) {

			if ( self::$settings['category'] == '1' ) {

				add_action( 'init', array( $this, 'register_rewrite_rules' ) );
				add_filter( 'post_type_link', array(
					$this,
					'post_type_link',
				), 10, 3 );

			}

		}

		do_action( 'af_link_init' );

		$this->load_pro();
	}

	/**
	 * Get Slug for Link
	 */
	public function get_slug() {
		if ( ! empty( self::$settings['slug'] ) ) {

			return self::$settings['slug'];

		} else {

			return self::$slug;

		}
	}

	/**
	 * Register Custom Post Type.
	 */
	public function register_affiliate_links() {
		$labels = array(
			'name'           => _x( self::$plugin_name, 'Post Type General Name', 'affiliate-links' ),
			'singular_name'  => _x( self::$singular_name, 'Post Type Singular Name', 'affiliate-links' ),
			'menu_name'      => __( self::$plugin_name, 'affiliate-links' ),
			'name_admin_bar' => __( self::$plugin_name, 'affiliate-links' ),
			'add_new_item'   => __( 'Add New Link', 'affiliate-links' ),
			'add_new'        => __( 'Add New Link', 'affiliate-links' ),
			'new_item'       => __( 'New Link', 'affiliate-links' ),
			'edit_item'      => __( 'Edit Link', 'affiliate-links' ),
			'update_item'    => __( 'Update Link', 'affiliate-links' ),
			'view_item'      => __( 'View Link', 'affiliate-links' ),
			'search_items'   => __( 'Search Link', 'affiliate-links' ),
		);

		$slug = $this->get_slug();

		$rewrite = array(
			'slug'       => $slug,
			'with_front' => FALSE,
			'pages'      => FALSE,
			'feeds'      => FALSE,
		);

		$args = array(
			'label'               => __( self::$singular_name, 'affiliate-links' ),
			'description'         => __( 'Affiliate Link Post Type', 'affiliate-links' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => FALSE,
			'public'              => TRUE,
			'show_ui'             => TRUE,
			'show_in_menu'        => TRUE,
			'menu_position'       => 100,
			'menu_icon'           => 'dashicons-admin-links',
			'show_in_admin_bar'   => TRUE,
			'show_in_nav_menus'   => TRUE,
			'can_export'          => TRUE,
			'has_archive'         => FALSE,
			'exclude_from_search' => TRUE,
			'publicly_queryable'  => TRUE,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);

		$args = apply_filters( 'af_link_register_post_type', $args );

		register_post_type( self::$post_type, $args );
	}

	/**
	 * Register Custom Post Type.
	 */
	public function register_affiliate_links_cat() {
		$labels = array(
			'name'          => _x( 'Categories', 'Taxonomy General Name', 'affiliate-links' ),
			'singular_name' => _x( 'Category', 'Taxonomy Singular Name', 'affiliate-links' ),
			'menu_name'     => __( 'Categories', 'affiliate-links' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => TRUE,
			'public'            => FALSE,
			'show_ui'           => TRUE,
			'show_admin_column' => TRUE,
			'show_in_nav_menus' => FALSE,
			'show_tagcloud'     => FALSE,
			'rewrite'           => FALSE,
		);

		$args = apply_filters( 'af_link_register_taxonomy', $args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $args );
	}

	public function register_rewrite_rules() {
		$slug = $this->get_slug();

		add_rewrite_rule( "$slug/([^/]+)?/?$", 'index.php?' . self::$post_type . '=$matches[1]', 'top' );
		add_rewrite_rule( "$slug/([^/]+)?/?([^/]+)?/?", 'index.php?' . self::$post_type . '=$matches[2]&' . self::$taxonomy . '=$matches[1]', 'top' );
	}

	/**
	 * Modify Post Type Link
	 */
	public function post_type_link( $permalink, $post_id, $leavename ) {
		$post = get_post( $post_id );

		if ( ! empty( $permalink ) ) {

			if ( $post->post_type == self::$post_type ) {

				$terms = wp_get_object_terms( $post->ID, self::$taxonomy );

				if ( ! empty( $terms ) ) {

					usort( $terms, '_usort_terms_by_ID' ); // order by ID

					$term = $terms[0]->slug;

					if ( ! empty( $term ) ) {

						$slug = $this->get_slug();

						$permalink = str_replace( $slug, $slug . '/' . $term, $permalink );

					}

				}

			}

		}

		return $permalink;
	}

	/**
	 * Activation Hook
	 */
	public function activation_hook() {
		// Register post type and taxonomy
		$this->register_affiliate_links();
		$this->register_affiliate_links_cat();

		// Flush the permalinks rules
		flush_rewrite_rules();

		if ( get_option( 'affiliate_links_settings' ) === FALSE ) {

			$preset_options = array(
				'slug'     => $this->get_slug(),
				'default'  => get_home_url(),
				'nofollow' => 1,
				'redirect' => 301,
			);

			add_option( 'affiliate_links_settings', $preset_options );

		}
	}

	/**
	 * Deactivation Hook
	 */
	public function deactivation_hook() {
		// Flush the permalinks rules
		flush_rewrite_rules();
	}

	/**
	 * Redirect affiliate link.
	 */
	public function redirect() {
		if ( is_singular( Affiliate_Links::$post_type ) ) {

			$post_id       = get_the_ID();
			$target_url    = get_post_meta( $post_id, '_affiliate_links_target', TRUE );
			$redirect_type = get_post_meta( $post_id, '_affiliate_links_redirect', TRUE );
			$nofollow      = get_post_meta( $post_id, '_affiliate_links_nofollow', TRUE );
			$iframe        = get_post_meta( $post_id, '_affiliate_links_iframe', TRUE );

			if ( empty( $target_url ) ) {

				if ( ! empty( self::$settings['default'] ) ) {
					$target_url = self::$settings['default'];
				} else {
					$target_url = home_url( '/' );
				}

			}

			$this->count_stats( $post_id );

			if ( empty( $redirect_type ) ) {

				if ( empty( self::$settings['redirect'] ) ) {
					$redirect_type = 301;
				} else {
					$redirect_type = (int) self::$settings['redirect'];
				}

			}

			if ( $nofollow ) {
				$nofollow_header = 'X-Robots-Tag: noindex, nofollow';
				$nofollow_header = apply_filters( 'af_link_nofollow_header', $nofollow_header );
				header( $nofollow_header, TRUE );
			}

			$target_url    = apply_filters( 'af_link_target_url', $target_url );
			$redirect_type = apply_filters( 'af_link_redirect_type', $redirect_type );

			if ( $iframe ) {
				do_action( 'af_link_before_iframe', $post_id, $target_url );

				require_once AFFILIATE_LINKS_PLUGIN_DIR . 'includes/affiliate-links-iframe.php';
				exit();
			} else {
				do_action( 'af_link_before_redirect', $post_id, $target_url, $redirect_type );

				wp_redirect( esc_url_raw( $target_url ), $redirect_type );
				exit();
			}

		}
	}

	/**
	 * Count link hits and update post_meta.
	 */
	public function count_stats( $post_id ) {
		$current = (int) get_post_meta( $post_id, '_affiliate_links_stat', TRUE );

		update_post_meta( $post_id, '_affiliate_links_stat', $current + 1 );
	}

	public function get_button_settings_template() {
		$template = AFFILIATE_LINKS_PLUGIN_DIR . '/admin/partials/wysiwyg-button-dialog.php';

		if ( file_exists( $template ) ) {
			require_once AFFILIATE_LINKS_PLUGIN_DIR . '/admin/partials/wysiwyg-button-dialog.php';
		}
	}

	public function get_links() {
		$args  = array(
			'post_type'      => 'affiliate-links',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		);
		$links = new WP_Query( $args );

		return $links->get_posts();
	}

	public function is_pro() {
		if ( is_dir( AFFILIATE_LINKS_PRO_PLUGIN_DIR ) ) {
			return TRUE;
		}

		return FALSE;
	}

	public function load_pro() {
		if ( ! $this->is_pro() ) {
			return '';
		}

		foreach ( glob( AFFILIATE_LINKS_PRO_PLUGIN_DIR . '/*.php' ) as $file ) {
			require( $file );
		}
	}

}