<?php

namespace Trex;

class Request {
	
	public $uri;
	public $uri_segments;
	public $query;
	
	public $method;
	
	public $params = array();
	public $data = array();

	public function __construct($server) {

		$this->method = $server['REQUEST_METHOD'];
		$this->data = $_REQUEST;
		
		$uri = $server['REQUEST_URI'];
		
		if (strpos($uri, '?') !== false) {
			$uri_parts = explode('?', $uri);
			$uri = $uri_parts[0];
			$this->query = $uri_parts[1];
		}
		
		$this->uri = $uri;
		$this->uri_segments = explode('/', $uri);
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
