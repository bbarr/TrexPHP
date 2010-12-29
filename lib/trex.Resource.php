<?php

namespace Trex;

abstract class Resource {
	
	public abstract function get($request);
	public abstract function post($request);
	public abstract function put($request);
	public abstract function delete($request);
	
	public function process($request) {
		$method = $request->method;
		$response = $this->$method($request);
		return $response;
	}
	
}

?>