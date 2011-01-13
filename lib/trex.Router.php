<?php

namespace Trex;

class Router {
	
	private $routes = array();
	private $locations = array();
	
	// caching locations for send->to and resource->at chaining
	private $sending = null;
	private $resource = null;
	
	public function scan($location) {
		
		if (!is_dir($location)) {
			return;	
		}
		
		$this->locations[] = $location;
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
 *  PRIVATE
 *  
 *  This class will grow when Trex supports namespaced resources
 *
 *  @param {String} - the name of the resource we are linking to
 *  @param {String} - the URI template we are routing
 */
class _Route {
	
	public $uri;
	public $uri_segments;
	public $resource;
	
	public function __construct($resource, $uri) {
		$this->uri = $uri;
		$this->uri_segments = explode('/', $uri);
		$this->resource = $resource;
	}
}

?>