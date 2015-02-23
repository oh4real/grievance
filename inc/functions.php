<?php
/**
	@todo Make this one big class and adjust all the calls and add tests.
*/

function grievance_ajaxurl() {
	echo sprintf("<script type='text/javascript'>if (typeof ajaxurl === 'undefined') var ajaxurl = '%s';</script>", admin_url('admin-ajax.php'));
}

function grievance_ajax_request() {
	// TODO: Make this pull from config.php in WP.
	$gc = new Grievance_Client(get_option('grievance_user_id'), get_option('grievance_password'), get_option('grievance_group_id'));
	$gc->login();
	$form = $gc->findall_asform();
	$html = $gc->process_table($form);

	// put somewhere in wp-content when for real
	echo $html;
	die();
}

function init_grievance_settings() { // whitelist options
	register_setting( 'grievance-settings-group', 'grievance_user_id' );
	register_setting( 'grievance-settings-group', 'grievance_password' );
	register_setting( 'grievance-settings-group', 'grievance_group_id' );
	add_settings_section('grievance_main', 'Settings at www.grievancego.com', 'plugin_section_text', 'grievance');
	add_settings_field('g_user_id', 'Username: ', 'grievance_input_user_id', 'grievance', 'grievance_main');
	add_settings_field('g_password', 'Password: ', 'grievance_input_password', 'grievance', 'grievance_main');
	add_settings_field('g_group_id', 'Group ID: ', 'grievance_input_group_id', 'grievance', 'grievance_main');
}

function get_input_text($option) {
	return sprintf('<input type="text" name="%s" id="%s" value="%s" />', $option, $option, get_option($option));
}

function grievance_input_user_id() {
	echo get_input_text('grievance_user_id');					
}

function grievance_input_group_id() {
	echo get_input_text('grievance_group_id');					
}

function grievance_input_password() {
	echo str_replace('"text"', '"password"', get_input_text('grievance_password'));					
}

function add_grievance_menu() {
	add_options_page (
            'Grievance',
            'Grievance',
            'manage_options',
            'grievance',
            'admin_settings_page'
        );
}

function admin_settings_page() {
	       echo '<div class="wrap">';
            echo '<h2>Grievance Settings</h2>';
            echo '<div id="poststuff">';
                echo '<div id="post-body" class="metabox-holder columns-2">';
                    echo '<div id="postbox-container-2" class="postbox-container">';
                        /* begin actual page contents */
                        echo '<form method="post" action="options.php">';
                        settings_fields( 'grievance-settings-group' );
                        do_settings_sections( 'grievance' );
                        submit_button(); 
                        echo '</form>';
                        /* end actual page contents */
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
}