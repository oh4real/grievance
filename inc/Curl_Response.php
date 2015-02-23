<?php

class Curl_Response {
	private $errno;
	private $errmsg;
	private $headers;
	private $content;
	private $cookies;
	private $responseInfo;

	private function __construct(array $info) {
		$this->responseInfo = $info;
	}

	public static function get(array $info) {
		return new self($info);
	}

	public function getErrno() {return $this->errno;}
	public function setErrno($val) { $this->errno = $val; return $this;}
	public function getErrmsg() {return $this->errmsg;}
	public function setErrmsg($val) { $this->errmsg = $val; return $this;}
	public function getHeaders() {return $this->headers;}
	public function setHeaders($val) { $this->headers = $val; return $this;}
	public function getContent() {return $this->content;}
	public function setContent($val) { $this->content = $val; return $this;}
	public function getCookies() {return $this->cookies;}
	public function setCookies($val) { $this->cookies = $val; return $this;}
	public function getResponseInfo() {return $this->responseInfo;}
}