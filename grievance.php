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

require_once('inc/Grievance_Settings.php');
require_once('inc/Grievance_Plugin.php');
$gr = new Grievance_Plugin();
$gs = new Grievance_Settings();

if ( is_admin() ){ // admin actions
	add_action( 'admin_menu', array($gs, 'add_plugin_menu'));
	add_action( 'admin_init', array($gs, 'init_plugin_settings' ));
} else {
	add_action('wp_head', array($gs, 'plugin_ajaxurl'));
}

add_action( 'wp_ajax_grievance_ajax_request', array($gr, 'plugin_ajax_request' ));
add_action( 'wp_ajax_nopriv_grievance_ajax_request', array($gr, 'plugin_ajax_request' ));
add_action( 'wp_ajax_grievance_ajax_form_request', array($gr, 'plugin_ajax_form_request' ));
add_action( 'wp_ajax_nopriv_grievance_ajax_form_request', array($gr, 'plugin_ajax_form_request' ));

