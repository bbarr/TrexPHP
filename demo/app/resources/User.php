<?php

class User extends Trex\Resource {
	
	public function get($request) {
		
		$response = new Trex\Response();
		
		$response->body = 'Look! It\'s User ' . $request->params['id'];
		
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