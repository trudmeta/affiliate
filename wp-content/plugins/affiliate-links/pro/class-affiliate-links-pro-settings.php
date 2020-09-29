<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
require_once AFFILIATE_LINKS_PLUGIN_DIR . 'admin/class-affiliate-links-settings.php';

class Affiliate_Links_Pro_Settings {

	public function __construct() {
		$this->add_fields();
		$this->update_tabs();
	}

	public function add_fields() {
		$options = array(
			array(
				'name'        => 'enable_ga',
				'title'       => 'Google Analytics',
				'type'        => 'checkbox',
				'tab'         => 'ga',
				'default'     => '0',
				'description' => 'Enable Google Analytics',
			),
			array(
				'name'        => 'ga_global_object',
				'title'       => 'Global Object Name',
				'type'        => 'text',
				'tab'         => 'ga',
				'default'     => 'ga',
				'description' => 'Enter the Google Analytics custom Global Object',
			),
			array(
				'name'        => 'ga_ev_category',
				'title'       => 'Event Category',
				'type'        => 'text',
				'tab'         => 'ga',
				'default'     => 'AF',
				'description' => 'Enter the Google Analytics event category',
			),
			array(
				'name'        => 'ga_ev_action',
				'title'       => 'Event Action',
				'type'        => 'text',
				'tab'         => 'ga',
				'default'     => 'click',
				'description' => 'Enter the Google Analytics event action',
			),
			array(
				'name'        => 'ga_ev_label',
				'title'       => 'Event Label',
				'type'        => 'text',
				'tab'         => 'ga',
				'default'     => 'Affiliate Link',
				'description' => 'Enter the Google Analytics event label',
			),
			array(
				'name'        => 'parameters_whitelist',
				'title'       => 'Parameters Whitelist',
				'type'        => 'text',
				'tab'         => 'general',
				'default'     => '',
				'description' => 'URL parameters which should be passed to the target URL (comma separated)',
			),
		);
		foreach ( $options as $field ) {
			Affiliate_Links_Settings::add_field( $field );
		}
	}

	public function update_tabs() {
		Affiliate_Links_Settings::add_tab( 'ga', 'Google Analytics' );
		Affiliate_Links_Settings::remove_tab( 'go_premium' );
	}

}