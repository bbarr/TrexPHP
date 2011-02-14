<?php

namespace Trex;

class Builder {
	
	private $frozen = false;
	private $stack = array();
	
	private function freeze() {

		// minus 2 to get index of last middleware
		$count = count($this->stack) - 2;
		while ($count > -1) {
			$this->stack[$count] = $this->stack[$count]($this->stack[$count + 1]);
			$count--;
		}
		
		$this->frozen = true;
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
	
	public function call($env) {
		
		if (!$this->frozen) return;
		
		// fires the middleware stack
		return $this->stack[0]->call($env);
	}
	
	public function __call($method, $args) {
		if ($method === 'use') {
			$this->uze($args[0]);
		}
	}
}

?>