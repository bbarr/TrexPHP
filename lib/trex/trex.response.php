<?php

namespace Trex;

/**
 *  - Trex Response -
 *
 *  Used by application to respond to client
 */
class Response {

	private $instance = null;

	// defaults
	public $body = array();
	public $status = 200;
	public $headers = array(
		'Content-Type' => 'text/plain'
	);
	
	/**
	 *  Builds response using standard application response format
	 *
	 *  @param {Array} 
	 */
	public function __construct($response) {

		if ($instance) {
			$this->instance->update($response);
			return $this->instance;
		}

		ob_start();
		$this->update($response);
		$this->instance = $this;
	}
	
	private function update($response) {
		
		$this->status = $response[0];
		
		foreach ($response[1] as $key => $val) {
			$this->header($key, $val);
		}
		
		foreach ($response[2] as $body_part) {
			$this->body[] = $body_part;
		}
	}
	
	/**
	 *  Sets header with $name => $value
	 *
	 *  @param {String}
	 *  @param {String}
	 */
	public function header($name, $value) {
		$this->headers[$name] = $value;
	}
	
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