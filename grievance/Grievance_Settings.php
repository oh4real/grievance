<?php 

require_once GRIEVANCE_ROOT . '/inc/Simple_Plugin_Settings.php';

class Grievance_Settings extends Simple_Plugin_Settings {

	// extend with const of your fields (user or site)
	const USER_ID = 'grievance_user_id';
	const PASSWORD = 'grievance_password';
	const GROUP_ID = 'grievance_group_id';

	// overwrite the Simple_Plugin_Settings to match your Plugin.
	const PLUGIN_NAMESPACE = 'grievance';
	const PLUGIN_SETTINGS_TITLE = 'Grievance Site Settings';
	const PLUGIN_SECTION = 'grievance_main';

	public function init_plugin_settings() { // whitelist options
		// register your site-level options
		register_setting( 'grievance-settings-group', self::GROUP_ID );

		// define a section for the Admin - Settings panel
		add_settings_section(static::PLUGIN_SECTION, 'Settings at www.grievancego.com', array($this, 'plugin_section_text'), static::PLUGIN_NAMESPACE);

		// Register the automagic of Settings panels
		add_settings_field('g_group_id', 'Group ID: ', array($this, 'generate_input_field'), static::PLUGIN_NAMESPACE, static::PLUGIN_SECTION, array('field' => self::GROUP_ID));
	}

	public function plugin_section_text() {
		// echo text to appear as sub-paragraph.
	}

	public function add_plugin_menu() {
		add_options_page (
	            'Grievance',
	            'Grievance',
	            'manage_options',
	            'grievance',
	            array($this, 'admin_settings_page')
	        );
	}
}