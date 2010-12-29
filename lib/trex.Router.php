<?php

namespace Trex;

class Router {
	
	private $routes = array();
	private $locations = array();
	
	private $sending = null;
	
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
	
	public function match($request) {
		
		foreach ($this->routes as $route) {
			
			if ($request->matches($route)) {
				
				$resource = $route->resource;
				
				$file = $this->locate_resource($resource);
				include($file);
				
				return new $resource();
			}
		}
		
		$response = new Response();
		$response->status = 404;
		$response->body = 'No route matched this URI.';
		$response->deliver();
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