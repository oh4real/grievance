<?php 

class Grievance_JsonView {
	private $status;
	private $html;
	private $data;
	private $errorMessage;

	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	public function setData($data) {
		$this->data = $data;
		return $this;
	}

	public function setHtml($html) {
		$this->html = $html;
		return $this;
	}

	public function setError($val) {
		$this->errorMessage = $val;
		return $this;
	}

	public function __toString() {
		return json_encode(array(
			'status' => $this->status,
			'data' => $this->data,
			'html' => $this->html,
			'errorMessage' => $this->errorMessage
		));
	}
}