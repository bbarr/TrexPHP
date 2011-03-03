<?php

namespace Trex;

/**
 *  - Trex Router - 
 *
 *  Responsible for handling routing
 */
class Router {
	
	private $routes = array();
	private $locations = array();
	
	public function scan($location) {
		
		if (!is_dir($location)) {
			return;	
		}
		
		$this->locations[] = $location;
	}
	
	public function route($url, $handler) {		
		
		if (is_callable($handler)) {
			
			// if handler is callback function
			$this->routes[] = new _Route($url, $handler, true);
		}
		else {
			
			// else just routing to a resource or a class::action
			$this->routes[] = new _Route($url, $handler);
		}
	}
	
	public function send($uri) {
		$this->sending = $uri;
		return $this;
	}
	
	public function to($resource) {
		$this->routes[] = new _Route($resource, $this->sending);
	}
	
	public function resource($resource) {
		$this->resource = $resource;
		return $this;
	}
	
	public function at($uri) {
		$this->routes[] = new _Route($this->resource, $uri);
		$this->routes[] = new _Route($this->resource, $uri . '/{id}');
	}
	
	public function match($request) {
		
		foreach ($this->routes as $route) {
			
			if ($request->matches($route)) {
				
				$resource = $route->resource;
				
				if (!class_exists($resource)) {
					$file = $this->locate_resource($resource);
					include($file);	
				}
				
				return new $resource();
			}
		}
		
		$this->resource_not_found();
	}
	
	private function locate_resource($resource) {
		
		$resource_file = $resource . '.php';
		
		foreach ($this->locations as $location) {
			
			$dir = scandir($location);
			foreach ($dir as $file) {
				if ($file === $resource_file) {
					return $location . '/' . $file;
				}
			}
		}
		
		$this->resource_not_found();
	}
	
	private function resource_not_found() {
		$response = new Response();
		$response->status = 404;
		$response->body = 'No route matched this URI.';
		$response->deliver();
	}
}

/**
 *  - Trex Route -
 *  
 *  @param {String} - the URI template we are routing
 *  @param {String|Function} - A callback function, or a resource name, or a controller:action
 */
class Route {
	
	public $uri;
	public $uri_segments;
	public $resource;
	public $callback;
	
	public function __construct($uri, $handler, $has_callback) {
		
		$this->uri = $uri;
		$this->uri_segments = explode('/', $uri);
		
		if ($has_callback) {
			$this->callback = $handler
		}
		else {
			$this->resource = $handler;	
		}
	}
}

?>