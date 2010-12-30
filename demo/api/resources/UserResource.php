<?php

class UserResource extends Trex\Resource {

	public function get($request) {
		$response = new Trex\Response();
		
		
		
		$response->status = 200;
		$response->body = json_encode($user);
		return $response;
	}
	
	public function post($request) {
		$response = new Trex\Response();
		
		return $response;
	}
	
	public function put($request) {
		$response = new Trex\Response();
		
		return $response;
	}
	
	public function delete($request) {
		$response = new Trex\Response();
		
		return $response;
	}
}

?>