<?php
global $args;
$args = $argv;

define('GRIEVANCE_ROOT', dirname(__FILE__));

require_once(GRIEVANCE_ROOT . '/Main/Grievance_Settings.php');
require_once(GRIEVANCE_ROOT . '/Main/Grievance_Plugin.php');

// Mock out WordPress global methods:
function get_current_user_id() {
	return 1;
}
function get_userdata($id) {
	return new WP_User('First', 'Name');
}
function get_option($key) {
	global $args;
	$options = array(
			Grievance_Settings::USER_ID =>  $args[1],
			Grievance_Settings::PASSWORD => $args[2],
			Grievance_Settings::GROUP_ID => $args[3]
		);
	return $options[$key];
}
function get_transient($key) {
	return false;
} 
function set_transient($cacheKey, $data, $time) {
	file_put_contents('/tmp/response.json', json_encode($data));
}
class WP_User {
	public $first_name;
	public $last_name;

	public function __construct($firstName, $lastName) {
		// magic Getters
		$this->first_name = $firstName;
		$this->last_name = $lastName;
	}
}

$gp = new Grievance_Plugin();
$_GET['filter'] = 'all';
$gp->plugin_ajax_request();