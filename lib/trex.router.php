<?php

namespace Trex;

class Router {
	
	public $routes = array();
	
	public function __construct($nss = null) {
		$this->nss = $nss;
	}
	
	public function add
	
	public function direct($request) {
		
		$segments = $request->uri_segments;
		$nss = $this->nss;
		
		if ($segments)
		
		$resource_name = $segments[0];
		
		$resource_name = ($this->nss) ? $this->apply_namespaces($segments) : $segments[0];
		$this->load($resource_name);
	}
	
}

?>