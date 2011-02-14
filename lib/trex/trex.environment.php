<?php

namespace Trex;

class Environment {
	
	static public INSTANCE = array();
	
	public function __construct($request) {
		print_r($request);
		$env = array(
			'REQUEST_METHOD' => '',
			'SCRIPT_NAME' => '',
			'PATH_INFO' => '',
			'QUERY_STRING' => '',
			'SERVER_NAME' => '',
			'SERVER_PORT' => '',
			'HTTP_VARIABLES' => array(),
			'trex.version' => [0,1],
			'trex.url_scheme' => ''
		);
	
		return self::DATA = $env;
	}
}
?>