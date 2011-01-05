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
	public $query;
	public $method;
	public $params = array();
	public $data = array();

	/**
	 *  Extracts neccessary data from the $_SERVER variable
	 *
	 *  @param {Array} - the $_SERVER variable for the current request
	 */
	public function __construct($server) {

		$this->method = $server['REQUEST_METHOD'];
		$this->data = $_REQUEST;
		
		$uri = $server['REQUEST_URI'];
		
		// remove query string from uri and store it seperately
		if (strpos($uri, '?') !== false) {
			$uri_parts = explode('?', $uri);
			$uri = $uri_parts[0];
			$this->query = $uri_parts[1];
		}
		
		$this->uri = $uri;
		$this->uri_segments = explode('/', $uri);
	}
	
	/**
	 *  This matches a given route to the request uri,
	 *  and if it matches, extracts params to Request::params
	 *
	 *  @param {String} - route to match
	 */
	public function matches($route) {
	
		if (count($route->uri_segments) !== count($this->uri_segments)) {
			return false;
		}
	
		foreach ($route->uri_segments as $index => $segment) {
			
			if ($segment === '') {
				continue;
			}
			
			if ($segment{0} === '{') {
				$this->params[preg_replace("/\{|\}/", '', $segment)] = $this->uri_segments[$index];
			}
			else {
				if ($segment !== $this->uri_segments[$index]) {
					return false;
				}
			}
		}

		return true;
	}
	
}

?>
