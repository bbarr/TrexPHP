<?php

namespace Trex;

class Request {
	
	public $uri;
	public $uri_segments;
	public $method;
	
	public $params = array();
	public $data = array();

	public function __construct($server) {
		$this->method = $server['REQUEST_METHOD'];
		$this->uri = $server['REQUEST_URI'];
		$this->uri_segments = explode('/', $this->uri);
	}

	public function matches($route) {
	
		if (count($route->uri_segments) !== count($this->uri_segments)) {
			return false;
		}
	
		foreach ($route->uri_segments as $index => $segment) {
			
			if ($segment === '') {
				continue;
			}
			
			if ($segment{0} === '{') {
				$this->params[preg_replace("/\{|\}/", '', $segment)] = $this->uri_segments[$index];
			}
			else {
				if ($segment !== $this->uri_segments[$index]) {
					return false;
				}
			}
		}
				
		return true;
	}
	
}

?>
