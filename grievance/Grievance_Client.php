<?php
require_once(GRIEVANCE_ROOT . '/lib/Curl_Lib.php');

class Grievance_Client {

	const GRIEVANCE_HOST = 'www.grievancego.com';
	const LOGON_PATH = '/GrievanceWeb/LogonPath.do';
	const FIND_PATH = '/GrievanceWeb/GrievanceFindPath.do';
	const GET_XLS_PATH = '/GrievanceWeb/grievanceSearchExport.action';

	private $jsessionId;
	private $userId;
	private $password;
	private $groupId;
	private $firstName;
	private $lastName;

	private $curlRequest;

	public function __construct() {
		$this->request = new Curl_Request(Curl_Request::HTTPS_PROTOCOL, self::GRIEVANCE_HOST);
		$this->jsessionId = Curl_Lib::get_web_page($this->request->setRoute(self::LOGON_PATH))->getCookies();
	}

	public function login() {
		$fields = array(
			"userId" => $this->userId,
			"password" => $this->password,
			"dispatch" => "logon"
			);
		$request = clone $this->request;
		$request->setRoute(self::LOGON_PATH . ";" . $this->jsessionId);
		$response = Curl_Lib::post_web_page($request, $fields, $this->jsessionId);
		$dom = new DOMDocument;
		$dom->loadHTML($response->getContent());
		$simpleXml = simplexml_load_string($dom->C14N());
		$fieldNameElements = $simpleXml->xpath("//td[@class='messagetext']");
		return !(bool)count($fieldNameElements);
	}

	public function fetchSearchPage() {
		$request = clone $this->request;
		$request->setRoute(self::FIND_PATH . '?dispatch=init')
			->setReferer(sprintf('%s/%s', $request->getHost(), self::FIND_PATH ));
		$content = Curl_Lib::get_web_page($request, $this->jsessionId)->getContent();
		preg_match('/<form (.*)<\/form>/s', $content, $matches);
		return str_replace('&nbsp;', '', preg_replace('/\s+/S', " ", $matches[0]));
	}

	public function fetchSearchResults() {
		$findAllParams = array(
		    "dispatch" => "find",
		    "groupId" => $this->groupId,
		    "sortOption" => "G",
		    "employeeFullName" => sprintf("fname:%s lname:%s", $this->firstName, $this->lastName)
		);
		$request = clone $this->request;
		$request->setRoute(self::FIND_PATH)
			->setReferer(sprintf('%s/%s', $request->getHost(), self::FIND_PATH . '?dispatch=init'));
		$content = Curl_Lib::post_web_page($request, $findAllParams, $this->jsessionId)->getContent();
		preg_match('/<form (.*)<\/form>/s', $content, $matches);
		return str_replace('&nbsp;', '', preg_replace('/\s+/S', " ", $matches[0]));
	}

	public function extractHtmlTable($xml) {
		// file_put_contents('/tmp/empty.html', $xml);
		$dom = new DOMDocument;
		$dom->loadHTML($xml);
		$tables = $dom->getElementsByTagName('table');

		$html;
		foreach ($tables as $table) {
		    if ($table->getAttribute('class') == 'results') {
				$html = str_replace('href="../', 'href="https://www.grievancego.com/', $table->C14N());
				$html = str_replace('images/pdf_image.jpg', 'http://www.adobe.com/images/pdficon_large.png', $html);
				$html = str_replace('href="Grievance', 'href="https://www.grievancego.com/Grievance', $html);
				$html = str_replace('GrievancePath.do?dispatch', 'GrievanceWeb/GrievancePath.do?dispatch', $html);
				$html = str_replace('>Re-Open</a>', ' target="_blank">Re-Open</a>', $html);
			}
		}
		return $html;
	}

	public function convertTableToArray($xml) {
		if (!strlen($xml)) {
			return array();
		}
		$simpleXml = simplexml_load_string($xml);
		$fieldNameElements = $simpleXml->xpath("//tr[@bgcolor='lightblue']");
		$keys = array();
		foreach ($fieldNameElements[0]->children() as $th) {
			$keys[] = preg_replace('/\W+/S', '', trim((string)$th));
		}

		$rows = $simpleXml->xpath("//table[@class='results']/tbody/tr[not(@bgcolor)]");
		$json = array();
		for ($i=1; $i<count($rows); $i += 3) {
			$record = array();
			$data = array_combine($keys, $rows[$i-1]->xpath('td'));
			foreach($data as $key => $val) {
				if ($val->count()) {
					// @todo: do some iterations
				} else {
					$record[$key] = (string) $val;
				}
			}
			$summary = $rows[$i]->xpath('td');
			$record['Summary'] = (string)$summary[0];
			$json[] = $record;
		}
		return $json;
	}

	public function setUserId($val) {
		$this->userId = $val;
	}

	public function setPassword($val) {
		$this->password = $val;
	}

	public function setGroupId($val) {
		$this->groupId = $val;
	}

	public function setWpUser(WP_User $user) {
		// magic Getters
		$this->firstName = $user->first_name;
		$this->lastName = $user->last_name;}

	public function toArray() {
		return array(
			'jsessionId' => $this->jsessionId,
			'userId' => 	$this->userId,
			'password' => 	$this->password,
			'groupId' => 	$this->groupId,
			);
	}
}