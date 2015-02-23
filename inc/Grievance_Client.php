<?php
require_once('Curl_Lib.php');

class Grievance_Client {

	const LOGON_PATH = 'https://www.grievancego.com/GrievanceWeb/LogonPath.do';
	const FIND_PATH = 'https://www.grievancego.com/GrievanceWeb/GrievanceFindPath.do';
	const GET_XLS_PATH = 'https://www.grievancego.com/GrievanceWeb/grievanceSearchExport.action';

	private $jsessionId;
	private $userId;
	private $password;
	private $groupId;

	public function __construct($userId, $password, $groupId) {
		$this->userId = $userId;
		$this->password = $password;
		$this->groupId = $groupId;
		$this->jsessionId = Curl_Lib::get_web_page(self::LOGON_PATH)->getCookies();
	}

	public function login() {
		$fields = array(
			"userId" => $this->userId,
			"password" => $this->password,
			"dispatch" => "logon"
			);
		Curl_Lib::post_web_page(self::LOGON_PATH . ";" . $this->jsessionId, $fields, $this->jsessionId);
		return;
	}

	public function findall_asform() {
		$findAllParams = array(
		    "dispatch" => "find",
		    "groupId" => $this->groupId
		);
		preg_match('/<form (.*)<\/form>/s', Curl_Lib::post_web_page(self::FIND_PATH, $findAllParams, $this->jsessionId)->getContent(), $matches);
		return str_replace("\r\n", " ", $matches[0]);
	}

	public function process_table($form) {
		$dom = new DOMDocument;
		$dom->loadHTML($form);
		$tables = $dom->getElementsByTagName('table');

		$html;
		foreach ($tables as $table) {
		    if ($table->getAttribute('class') == 'results') {
				$html = str_replace('href="../', 'href="https://www.grievancego.com/', $table->C14N());
				$html = str_replace('images/pdf_image.jpg', 'http://www.adobe.com/images/pdficon_large.png', $html);
				$html = str_replace('href="Grievance', 'href="https://www.grievancego.com/Grievance', $html);
				$html = str_replace('>Re-Open</a>', ' target="_blank">Re-Open</a>', $html);
			}
		}
		return $html;
	}
}