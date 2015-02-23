<?php
/**
 * Plugin Name: Grievance
 * Plugin URI: http://www.oh4real.com/grievance
 * Description: A brief description of the plugin.
 * Version: 0.0.1
 * Author: David Byrd
 * Author URI: http://www.oh4real.com
 * Network: false
 * License: GPL2
 */
error_reporting(E_ERROR | E_PARSE);

require_once('inc/Grievance_Client.php');
require_once('inc/functions.php');

if ( is_admin() ){ // admin actions
	add_action( 'admin_menu', 'add_grievance_menu' );
	add_action( 'admin_init', 'init_grievance_settings' );
} else {
	add_action('wp_head','grievance_ajaxurl');
}
add_action( 'wp_ajax_grievance_ajax_request', 'grievance_ajax_request' );
add_action( 'wp_ajax_nopriv_grievance_ajax_request', 'grievance_ajax_request' );

