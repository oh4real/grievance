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

/** 
1. Upload the zippded plugin.
2. Activate the plugin.
3. Go to wp-admin : Settings : Grievance and add username, password and IGS# (log in to grievancego.com and ID is in top right corner).
4. On the page you want this rendered, add id of includedContent like below: 
	<div id="includedContent"></div> 
5. Add this inline JS to run on the page:
	jQuery(document).ready(function($){
		$.ajax({
		        url: ajaxurl,
		        data: {
		            'action':'grievance_ajax_request'
		        }, 
		        beforeSend:function() {
		            $("#includedContent").html("<img src='/wp-content/plugins/grievance/images/ajax-loader.gif' class='grievance-loader'>");
		        },
		        success:function(data) {
		            // This outputs the result of the ajax request
		            $("#includedContent").html(data);
		        },
		        error: function(errorThrown){
		            $("#includedContent").html("There was a problem. Please check with website administrator.");
		            console.log(errorThrown);
		        }
		    });
	 });


*/
