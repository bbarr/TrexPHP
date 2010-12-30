<?php

namespace Trex;

abstract class Resource {

	public function process($request) {
		$method = $request->method;
		$response = $this->$method($request);
		return $response;
	}
}

?>