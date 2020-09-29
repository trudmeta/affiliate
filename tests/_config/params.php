<?php
/**
 * Loads configuration parameters.
 *
 * Don't modify this file directly.
 * Create and use 'params.local.php' instead.
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package PluginName
 * @author  {AUTHOR}
 */

//global $argv;
//
//if ( ! in_array( 'acceptance', $argv, true ) ) {
//	return [];
//}
//
//$config = '.codeception/_config/params.github-actions.php';
//if ( in_array( 'github-actions', $argv, true ) && file_exists( $config ) ) {
//	return include $config;
//}
//
//$config = '.codeception/_config/params.local.php';
//if ( file_exists( $config ) ) {
//	return include $config;
//}
//
//die( "No valid config provided.\nPlease use 'params.example.php' as a template to create your own 'params.local.php'.\n" );

return [
    'WP_URL'            => 'http://wp.site/',
    'WP_DATA_DIR'       => 'D:\OSPanel\domains\wp.site\tests\_data',
    'WP_ADMIN_USERNAME' => 'admin',
    'WP_ADMIN_PASSWORD' => '123',
    'WP_ADMIN_PATH'     => '/wp-admin',
    'DB_HOST'           => 'localhost',
    'DB_NAME'           => 'acceptance_db',
    'DB_USER'           => 'root',
    'DB_PASSWORD'       => '',
    'DB_TABLE_PREFIX'   => 'dp_',
];