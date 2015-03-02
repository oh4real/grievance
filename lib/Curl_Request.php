<?php

class Curl_Request {
	const HTTP_PROTOCOL = 'http';
	const HTTPS_PROTOCOL = 'https';
	private $host;
	private $route;
	private $referer = '';
	private $origin = '';
	private $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36';

	public function __construct($protocol, $host) {
		$this->host = sprintf('%s://%s', $protocol, rtrim($host, '/'));
	}

	public function setReferer($referer) {
		$this->referer = $referer;
		return $this;
	}
	public function getReferer() {
		return $this->referer;
	}
	public function setOrigin($origin) {
		$this->origin = $origin;
		return $this;
	}
	public function getOrigin() {
		return $this->origin;
	}
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
		return $this;
	}
	public function getUserAgent() {
		return $this->userAgent;
	}
	public function setRoute($route) {
		$this->route = $route;
		return $this;
	}
	public function getRoute() {
		return $this->route;
	}
	public function getHost() {
		return $this->host;
	}
	/** Helper Methods */
	public function getUrl() {
		return sprintf('%s/%s', $this->host, ltrim($this->route, '/'));
	}
}