<?php

namespace Trex;

class Router {
	
	private $routes = array();
	private $sending = null;
	
	public function send($uri) {
		$this->sending = $uri;
		return $this;
	}
	
	public function to($resource_name) {
		$this->routes[] = new _Route($resource_name, $this->sending);
	}
	
	public function direct($request) {
		foreach ($this->routes as $route) {
			if ($route->matches($request)) {
				return new $route->target();
			}
		}
	}
}

class _Route {
	
	public $uri;
	public $target;
	public $resource_name;
	
	public function __construct($resource_name, $uri) {
		$this->uri = $uri;
		$this->resource_name = $resource_name;
	}
	
	public function matches($request) {
		
	}
}

?>