<?php

include "models/UserModel.php";

class User extends Trex\Resource {

	private $users;
	
	public function __construct() {
		$this->users = new UserModel();
	}

	public function get($request) {
		$response = new Trex\Response();
		
		if (isset($request->params['id'])) {
			$result = $this->users->fetch($request->params['id']);
		}
		else {
			$result = $this->users->fetch();
		}
		
		$response->status = 200;
		$response->body = json_encode($result);
		
		return $response;
	}
	
	public function post($request) {
		$response = new Trex\Response();

		$user = $this->users->create($request->data['name']);
		
		if ($user) {
			$response->status = 201;
			$response->body = json_encode($user);
		}
		else {
			$response->status = 500;
		}

		return $response;
	}
	
	public function put($request) {
		$response = new Trex\Response();

		return $response;
	}
	
	public function delete($request) {
		$response = new Trex\Response();

		$result = $this->users->delete(urldecode($request->params['id']));
		
		if ($result) {
			$response->status = 200;
		}
		else {
			$response->status = 404;
		}
		
		return $response;
	}
}

?>