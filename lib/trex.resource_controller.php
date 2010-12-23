<?php

namespace Trex;

abstract class ResourceController {
	
	public abstract function get($config = array());
	public abstract function post($data);
	public abstract function put($data);
	public abstract function delete();
	
	public function process($resource) {
		$method = $resource->method;
		$response = $this->$method();
		return $response;
	}
	
}

?>