<?php

namespace Trex;

class Request {
	
	public $uri;
	public $uri_segments;
	public $method;

	public function __construct() {
		$server = $_SERVER;
		$this->method = $server['REQUEST_METHOD'];
		$this->uri = $server['REQUEST_URI'];
		$this->uri_segments = explode('/', $this->uri);
	}
	
}

?>
