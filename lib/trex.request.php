<?php

namespace Trex;

class Request {
	
	public $uri;
	public $method;
	public $resource;
		
	public function __construct() {
		$server = $_SERVER;
		$this->method = $server['REQUEST_METHOD'];
		$this->uri = $server['REQUEST_URI'];
	}
	
	
}

?>
