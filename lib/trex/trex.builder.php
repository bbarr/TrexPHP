<?php

namespace Trex;

class Builder {
	
	private $frozen = false;
	private $stack = array();
	
	private function freeze() {
	
		$this->frozen = true;
		
		// minus 2 to get index of last middleware
		$count = count($this->stack) - 2;
		while ($count > -1) {
			$this->stack[$count] = $this->stack[$count]($this->stack[$count + 1]);
			$count--;
		}
	}
	
	private function to_stack($app) {
		if ($this->frozen) return;
		$this->stack[] = $app;
	}
	
	private function uze($middleware) {
		$this->to_stack(function($app) use ($middleware) { return new $middleware($app); });
	}
	
	public function run($app) {
		$this->to_stack($app);
		$this->freeze();
	}
	
	public function call($request) {
		
		if (!$this->frozen) return;
		
		$request = new Request($request);

		// fires the middleware stack
		$result = $this->stack[0]->call($request);
		
		$response = new Response($result);		
		$response->deliver();
	}
	
	public function __call($method, $args) {
		if ($method === 'use') {
			$this->uze($args[0]);
		}
	}
}

?>