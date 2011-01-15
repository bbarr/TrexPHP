<?php

/**
 **  TrexPHP - For creating simple RESTful API's
 **  
 **  Classes: Request, Response, Router, Resource
 **
 **  @author Brendan Barr brendanbarr.web@gmail.com
 **/

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

/**
 *  - Trex Response -
 *
 *  Used by application to respond to client
 */
class Response {

	public static $HTTP_CODES = array(
		100 => 'Continue',  
		101 => 'Switching Protocols',  
		200 => 'OK',  
		201 => 'Created',  
		202 => 'Accepted',  
		203 => 'Non-Authoritative Information',  
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',  
		301 => 'Moved Permanently',  
		302 => 'Found',  
		303 => 'See Other',  
		304 => 'Not Modified',  
		305 => 'Use Proxy',  
		306 => '(Unused)',  
		307 => 'Temporary Redirect',  
		400 => 'Bad Request',  
		401 => 'Unauthorized',  
		402 => 'Payment Required',  
		403 => 'Forbidden',  
		404 => 'Not Found',  
		405 => 'Method Not Allowed',  
		406 => 'Not Acceptable',  
		407 => 'Proxy Authentication Required',  
		408 => 'Request Timeout',  
		409 => 'Conflict',  
		410 => 'Gone',  
		411 => 'Length Required',  
		412 => 'Precondition Failed',  
		413 => 'Request Entity Too Large',  
		414 => 'Request-URI Too Long',  
		415 => 'Unsupported Media Type',  
		416 => 'Requested Range Not Satisfiable',  
		417 => 'Expectation Failed',  
		500 => 'Internal Server Error',  
		501 => 'Not Implemented',  
		502 => 'Bad Gateway',  
		503 => 'Service Unavailable',  
		504 => 'Gateway Timeout',  
		505 => 'HTTP Version Not Supported'  
	);

	/**
	 *  RESPONSE PROPERTIES WITH DEFAULTS
	 */
	public $body;
	public $status = 200;
	public $headers = array(
		'Content-Type' => 'text/html'
	);
	
	public function header($name, $value) {
		$this->headers[$name] = $value;
	}
	
	/**
	 *  Execute the response and deliver to client
	 */
	public function deliver() {
		
		// set HTTP type and response status
		header('HTTP/1.1 ' . $this->status . ' ' . self::$HTTP_CODES[$this->status]);
		
		if ($this->body) {
			$this->header('Content-Length', strlen($this->body));
		}
		
		// cycle through and call custom headers
		foreach ($this->headers as $name => $value) {
			header($name . ': ' . $value);
		}
		
		// finally, respond to the client
		exit($this->body);
	}
}

/**
 *  - Trex Router - 
 *
 *  Responsible for handling routing
 */
class Router {
	
	private $routes = array();
	private $locations = array();
	
	public function scan($location) {
		
		if (!is_dir($location)) {
			return;	
		}
		
		$this->locations[] = $location;
	}
	
	public function route($url, $handler) {		
		
		if (is_callable($handler)) {
			
			// if handler is callback function
			$this->routes[] = new _Route($url, $handler, true);
		}
		else {
			
			// else just routing to a resource or a class::action
			$this->routes[] = new _Route($url, $handler);
		}
	}
	
	public function send($uri) {
		$this->sending = $uri;
		return $this;
	}
	
	public function to($resource) {
		$this->routes[] = new _Route($resource, $this->sending);
	}
	
	public function resource($resource) {
		$this->resource = $resource;
		return $this;
	}
	
	public function at($uri) {
		$this->routes[] = new _Route($this->resource, $uri);
		$this->routes[] = new _Route($this->resource, $uri . '/{id}');
	}
	
	public function match($request) {
		
		foreach ($this->routes as $route) {
			
			if ($request->matches($route)) {
				
				$resource = $route->resource;
				
				if (!class_exists($resource)) {
					$file = $this->locate_resource($resource);
					include($file);	
				}
				
				return new $resource();
			}
		}
		
		$this->resource_not_found();
	}
	
	private function locate_resource($resource) {
		
		$resource_file = $resource . '.php';
		
		foreach ($this->locations as $location) {
			
			$dir = scandir($location);
			foreach ($dir as $file) {
				if ($file === $resource_file) {
					return $location . '/' . $file;
				}
			}
		}
		
		$this->resource_not_found();
	}
	
	private function resource_not_found() {
		$response = new Response();
		$response->status = 404;
		$response->body = 'No route matched this URI.';
		$response->deliver();
	}
}

/**
 *  - Trex Route -
 *  
 *  @param {String} - the URI template we are routing
 *  @param {String|Function} - A callback function, or a resource name, or a controller:action
 */
class Route {
	
	public $uri;
	public $uri_segments;
	public $resource;
	public $callback;
	
	public function __construct($uri, $handler, $has_callback) {
		
		$this->uri = $uri;
		$this->uri_segments = explode('/', $uri);
		
		if ($has_callback) {
			$this->callback = $handler
		}
		else {
			$this->resource = $handler;	
		}
	}
}

/**
 *  - Trex Resource -
 */
abstract class Resource {

	public function process($request) {
		$method = $request->method;
		$response = $this->$method($request);
		return $response;
	}
}

?>