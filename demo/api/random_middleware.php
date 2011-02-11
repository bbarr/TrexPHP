<?php

class ToUpper {
	
	private $app;
	private $apps;
	
	public function __construct($app) {
		$this->app = $app;
	}
	
	public function call($env) {
		list($status, $headers, $body) = $this->app->call($env);
		return array($status, $headers, strtoupper($body));
	}
}

class YellIt {
	
	private $app;
	
	public function __construct($app) {
		$this->app = $app;
	}
	
	public function call($env) {
		list($status, $headers, $body) = $this->app->call($env);
		return array($status, $headers, $body . '!!!!!!');
	}
}

class PretendWeMadeSomething {
	
	private $app;
	
	public function __construct($app) {
		$this->app = $app;
	}
	
	public function call($env) {
		list($status, $headers, $body) = $this->app->call($env);
		return array(201, $headers, $body);
	}
}

?>