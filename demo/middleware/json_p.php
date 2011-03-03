<?php

class JsonP {
	
	public $app;
	
	public function __construct($app) {
		$this->app = $app;
	}
	
	public function call($env) {

		$response_array = $this->app->call($env);
		
		// create response, request helpers
		$response = Trex\Response::create($response_array);
		$request = Trex\Request::create($env);

		// if it is json response, format it
		if ($response->is_type('text/json')) {

			$callback = $request->param('callback');
			if ($callback) {

				// format body
				$response->body = $this->pad($callback, $response->body);
				
				// change headers to js
				$response->type('text/javascript');
			}

		}

		return $response->to_array();
	}
	
	private function pad($callback, $data) {
		$data = implode('', $data);
		return array("$callback($data)");
	}
	
}
?>