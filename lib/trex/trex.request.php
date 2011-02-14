<?php

namespace Trex;

/**
 *  - Trex Request -
 *
 *  This creates an abstracted request object capable
 *  of matching routes and extracting params.. among other things.
 */
class Request {
	
	public $uri;
	public $uri_segments;
	public $params = array();
	public $data = array();
	
	// this is what applications will be expecting
	public $env = array();

	/**
	 *  Extracts necessary data from the $_SERVER variable
	 *
	 *  @param {Array} - the $_SERVER variable for the current request
	 */
	public function __construct($server) {
		
		$env = array();
		
		$env['HTTP_VARIABLES'] = array();
		foreach ($server as $key => $value) {
			$env['HTTP_VARIABLES'][$key] = $value;
		}
		
		$env['REQUEST_METHOD'] = $server['REQUEST_METHOD'];
		$env['SCRIPT_NAME'] = $server['SCRIPT_NAME'];
		$env['PATH_INFO'] = '';
		$env['QUERY_STRING'] = $server['QUERY_STRING'];
		$env['SERVER_NAME'] = $server['SERVER_NAME'];
		$env['SERVER_PORT'] = $server['SERVER_PORT'];
		$env['trex.version'] = \Trex::$VERSION;
		$env['trex.uri'] = '';
		$env['trex.segments'] = array();
		$env['trex.url_scheme'] = (isset($server['HTTPS'])) ? 'HTTPS' : 'HTTP';

		$this->method = $server['REQUEST_METHOD'];
		$this->data = $_REQUEST;
		
		// remove query string from uri and store it seperately
		if (strpos($uri, '?') !== false) {
			$uri_parts = explode('?', $uri);
			$uri = $uri_parts[0];
		}
		
		$env['trex.uri'] = $uri;
		$env['trex.segments'] = explode('/', $uri);
	}
}

?>