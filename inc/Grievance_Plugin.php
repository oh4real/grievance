<?php
/**
	@todo Make this one big class and adjust all the calls and add tests.
*/

require_once 'Simple_Plugin_Options.php';

class Grievance_Plugin extends Simple_Plugin_Options {

	const USER_ID = 'grievance_user_id';
	const PASSWORD = 'grievance_password';

	protected $pluginSettingsTitle = 'Grievance Settings';

	public function __construct() {
		$this->pluginNamespace = 'grievance';
	}

	public function plugin_ajax_request() {
		error_reporting(E_ERROR | E_PARSE);
		$wpUserId = get_current_user_id();
		if ($options = $this->getGrievanceOptions()) {
			$gc = new Grievance_Client();
			$gc->setUserId($options[self::USER_ID]);
			$gc->setPassword($options[self::PASSWORD]);
			$gc->setGroupId(get_option('grievance_group_id'));
			$gc->setWpUser(get_userdata($wpUserId));
			$gc->login();
			$form = $gc->findall_asform();

			echo json_encode(array('status' => '200', 'data' => $gc->process_table($form)));
			// echo json_encode(array('status' => '200', 'body' => $gc->toArray()));
			die();
		} else {
			echo json_encode(array('status' => '404', 'data' => array('user_id' => $wpUserId)));
			die();
		}
	}
 
	public function plugin_ajax_form_request() {
		$wpUserId = get_current_user_id();
		// @todo Use a validator for this shiz for presence and validity
		if (array_key_exists('formData', $_POST)) {
			$formData = $_POST['formData'];
		}
		if ($formData[self::USER_ID] !== get_user_option(self::USER_ID, $wpUserId) && !update_user_option($wpUserId, self::USER_ID, $formData[self::USER_ID])) {
			echo json_encode(array('status' => '409',
				'errorMessage' => 'Unable to save Grievance Username'));
			die;
		}
		if ($formData[self::PASSWORD] !== get_user_option(self::PASSWORD, $wpUserId) && !update_user_option($wpUserId, self::PASSWORD, $formData[self::PASSWORD])) {
			echo json_encode(array('status' => '409',
				'errorMessage' => 'Unable to save Grievance Password'));
			die;
		}
		echo json_encode(
				array(
					'status' => '200',
					'data' => $this->getGrievanceOptions())
			);
		die;
	}

	private function getGrievanceOptions() {
		$user_id = get_user_option(self::USER_ID, $wpUserId);
		$pass = get_user_option(self::PASSWORD, $wpUserId);
		if (!$user_id || !$pass) {
			return false;
		}
		return array(
			self::USER_ID => $user_id, 
			self::PASSWORD => $pass
		);
	}

	public function init_plugin_settings() { // whitelist options
		register_setting( 'grievance-settings-group', self::USER_ID );
		register_setting( 'grievance-settings-group', self::PASSWORD );
		register_setting( 'grievance-settings-group', 'grievance_group_id' );
		add_settings_section('grievance_main', 'Settings at www.grievancego.com', array($this, 'plugin_section_text'), 'grievance');
		add_settings_field('g_user_id', 'Username: ', array($this, 'generate_input_field'), 'grievance', 'grievance_main', array('field' => self::USER_ID, 'type' => 'text'));
		add_settings_field('g_password', 'Password: ', array($this, 'generate_input_field'), 'grievance', 'grievance_main', array('field' => self::PASSWORD, 'type' => 'password'));
		add_settings_field('g_group_id', 'Group ID: ', array($this, 'generate_input_field'), 'grievance', 'grievance_main', array('field' => 'grievance_group_id'));
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