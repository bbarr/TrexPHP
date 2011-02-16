<?php

namespace Trex;

/**
 *  - Trex Response -
 *
 *  Used by application to respond to client
 */
class Response {

	private static $instance;

	// defaults
	public $body = array();
	public $status = 200;
	public $headers = array( 'Content-Type' => 'text/plain' );

	/**
	 *  Called once, on first initialization
	 */
	public function __construct() {
		
		// start buffer
		ob_start();
	}
	
	/**
	 *  Creates/returns an instance of Trex\Response
	 *
	 *  @param {Array} 
	 */	
	public static function create($response = null) {
		
		if ( !(self::$instance instanceof Response) ) {
			self::$instance = new Response;
		}

		return ($response) ? self::$instance->update($response) : self::$instance; 
	}
	
	/**
	 *  Assigns response data to proper props
	 *
	 *  @param {Array}
	 */
	public function update($response) {	
		
		$this->status = $response[0];
		$this->headers = $response[1];
		$this->body = $response[2];
		
		return $this;
	}
		
	/**
	 *  Sets header with $name => $value
	 *
	 *  @param {String}
	 *  @param {String}
	 */
	public function header($name, $value) { $this->headers[$name] = $value; }
	
	/**
	 *  Confirms the response's Content-Type header 
	 *
	 *  @param {String} 
	 */
	public function is_type($type) { return $this->headers['Content-Type'] === $type; }
	
	/**
	 *  Sets the response's Content-Type header 
	 *
	 *  @param {String} 
	 */
	public function type($type) { $this->headers['Content-Type'] = $type; }
	
	public function to_array() { return array($this->status, $this->headers, $this->body); }
	
	/**
	 *  Execute the response and deliver to client
	 */
	public function deliver() {
				
		// set HTTP type and response status
		header('HTTP/1.1 ' . $this->status . ' ' . self::$http_codes[$this->status]);

		// stringify body
		$this->body = implode('', $this->body);

		// if status is 204 or 304, skip content-type and content-length
		if (in_array($this->status, array(204, 304))) {
			$this->body = '';
			unset($this->headers['Content-Length']);
			unset($this->headers['Content-Type']);
		}
		else {
			$this->header('Content-Length', strlen($this->body));
		}
		
		// cycle through and call custom headers
		foreach ($this->headers as $name => $value) {
			header($name . ': ' . $value);
		}

		echo $this->body;

		ob_flush();
	}
	
	public static $http_codes = array(
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
}

?>