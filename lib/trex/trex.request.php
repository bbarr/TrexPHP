<?php

namespace Trex;

/**
 *  - Trex Request -
 *
 *  This creates an abstracted request object capable
 *  of matching routes and extracting params.. among other things.
 */
class Request {
		
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
		$env['pack.version'] = \Pack::$version;
		$env['pack.uri'] = '';
		$env['pack.segments'] = array();
		$env['pack.url_scheme'] = (isset($server['HTTPS'])) ? 'HTTPS' : 'HTTP';

		$env['pack.data'] = $_REQUEST;
		
		// remove query string from uri and store it seperately
		if (strpos($uri, '?') !== false) {
			$uri_parts = explode('?', $uri);
			$uri = $uri_parts[0];
		}
		
		$env['pack.uri'] = $uri;
		$env['pack.segments'] = explode('/', $uri);

		$this->env = $env;
	}
}

?>