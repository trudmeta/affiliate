<?php
/**
 * Plugin Name: Affiliate Links
 * Description: A powerful tool for managing and masking affiliate links. Perfect for SEO, advertising and affiliate marketing activities.
 * Version: 7.5.5
 * Author: Custom4Web
 * Author URI:  https://www.custom4web.com/ Text
 * Domain: affiliate-links
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'AFFILIATE_LINKS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AFFILIATE_LINKS_PRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) . 'pro' );
define( 'AFFILIATE_LINKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AFFILIATE_LINKS_PLUGIN_DIR . 'includes/class-affiliate-links.php';

/**
 * Begins execution of the plugin.
 */
$Affiliate_Links = new Affiliate_Links();

/**
 * Activation/deactivation stuff
 */
register_activation_hook( __FILE__, array(
	$Affiliate_Links,
	'activation_hook',
) );
register_deactivation_hook( __FILE__, array(
	$Affiliate_Links,
	'deactivation_hook',
) );