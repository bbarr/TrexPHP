<?php

namespace Trex;

class Builder {
	
	public $frozen = false;
	public $stack = array();
	
	private function freeze() {

		$this->frozen = true;

		// count back through middleware in stack assign each middleware the app that immediately follows it
		for ($count = count($this->stack) - 2; $count > -1; $count--) {
			$this->stack[$count] = $this->stack[$count]($this->stack[$count + 1]);
		}
	}
	
	private function to_stack($app) {
		
		if ($this->frozen) {
			throw new \Exception('Already have an end-app in the stack');
		}
		
		$this->stack[] = $app;
	}
	
	public function filter($middleware) {
		$this->to_stack(function($app) use ($middleware) { return new $middleware($app); });
	}
	
	public function run($app) {
		$this->to_stack($app);
		$this->freeze();
	}
	
	public function call($env) {
		
		if (!$this->frozen) {
			throw new \Exception('Missing end-app in stack');
		}

		// fires the middleware stack
		$response = $this->stack[0]->call($env);

		$response = Response::create($response);
		$response->deliver();
	}
}

?>