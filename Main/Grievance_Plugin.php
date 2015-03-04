<?php
/**
	@todo Make this one big class and adjust all the calls and add tests.
*/

require_once GRIEVANCE_ROOT . '/Main/Grievance_JsonView.php';
require_once GRIEVANCE_ROOT . '/Main/Grievance_Settings.php';
require_once GRIEVANCE_ROOT . '/Main/Grievance_Client.php';

class Grievance_Plugin {

	public function plugin_ajax_request() {
		error_reporting(E_PARSE);
		$response = new Grievance_JsonView;
		$wpUserId = get_current_user_id();
		if ($options = $this->getGrievanceOptions()) {
			$gc = new Grievance_Client();
			$gc->setUserId($options[Grievance_Settings::USER_ID]);
			$gc->setPassword($options[Grievance_Settings::PASSWORD]);
			$gc->setGroupId(get_option(Grievance_Settings::GROUP_ID));
			$gc->setWpUser(get_userdata($wpUserId));
			if (!$gc->login()) {
				exit ($response->setStatus(401));
			}
			// $resp = $gc->fetchSearchPage();file_put_contents('/tmp/response.xml', $resp);
			if (array_key_exists('filter', $_GET) && $_GET['filter'] == 'all'){
				$results = $gc->fetchAllSearchResults();
			} else {
				$results = $gc->fetchSearchResults();
			}
			$xmlString = $gc->extractHtmlTable($results);
			$data = strlen($xmlString) ? $gc->convertTableToArray($xmlString) : exit ($response->setStatus(404));

			exit ($response->setStatus(200)->setData($data));
		} else {
			exit ($response->setStatus(401));
		}
		exit ($response->setStatus(500));
	}
 
	public function plugin_ajax_form_request() {
		$wpUserId = get_current_user_id();
		$response = new Grievance_JsonView();
			
		// @todo Use a validator for this shiz for presence and validity
		if (array_key_exists('formData', $_POST)) {
			$formData = $_POST['formData'];
		}
		if ($formData[Grievance_Settings::USER_ID] !== get_user_option(Grievance_Settings::USER_ID, $wpUserId) && !update_user_option($wpUserId, Grievance_Settings::USER_ID, $formData[Grievance_Settings::USER_ID])) {
			exit ($response->setStatus(409)
					->setError('Unable to save Grievance Username'));
		}
		if ($formData[Grievance_Settings::PASSWORD] !== get_user_option(Grievance_Settings::PASSWORD, $wpUserId) && !update_user_option($wpUserId, Grievance_Settings::PASSWORD, $formData[Grievance_Settings::PASSWORD])) {
			exit ($response->setStatus(409)
					->setError('Unable to save Grievance Password'));
		}
		exit ($response->setStatus(200)
				->setData($this->getGrievanceOptions()));
	}

	private function getGrievanceOptions() {
		$user_id = ($o = get_option(Grievance_Settings::USER_ID)) ? $o : get_user_option(Grievance_Settings::USER_ID, $wpUserId);
		$pass = ($o = get_option(Grievance_Settings::PASSWORD)) ? $o : get_user_option(Grievance_Settings::PASSWORD, $wpUserId);
		if (!$user_id || !$pass) {
			return false;
		}
		return array(
			Grievance_Settings::USER_ID => $user_id, 
			Grievance_Settings::PASSWORD => $pass
		);
	}
}