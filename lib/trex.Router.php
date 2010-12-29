<?php

namespace Trex;

class Router {
	
	private $routes = array();
	private $locations = array();
	
	private $sending = null;
	
	public function scan($location) {
		$this->locations[] = $location;
	}
	
	public function send($uri) {
		$this->sending = $uri;
		return $this;
	}
	
	public function to($resource) {
		$this->routes[] = new _Route($resource, $this->sending);
	}
	
	public function direct($request) {
		foreach ($this->routes as $route) {
			if ($route->matches($request, $this->locations)) {
				return new $route->resource();
			}
		}
	}
}

class _Route {
	
	public $uri;
	public $resource;
	
	public function __construct($resource, $uri) {
		$this->uri = $uri;
		$this->resource = $resource;
	}
	
	public function matches($request, $locations) {
		
		foreach ($locations as $location) {
			if ($this->scan_location($location)) {
				return true;
			}
		}
		
	}
	
	private function scan_location($location) {

		$files = scandir($location);
		
		foreach ($files as $file) {
			
			if ($file{0} === '.') {
				continue;
			}
			
			if ($file === $this->resource . '.php') {
				include($location . '/' . $file);
				return true;
			}
		}
	}
}

?>