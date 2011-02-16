<?php

class BuilderTests extends PHPUnit_Framework_TestCase {
		
	public $builder;
		
	public function setUp() {
		$this->builder = new Trex\Builder;
	}
	
	public function test_returns_builder() {
		$this->assertInstanceOf('Trex\Builder', $this->builder);
	}
	
	public function test_adds_middleware() {
		$this->builder->filter('someMiddleware');
		$this->assertArrayHasKey(0, $this->builder->stack);
	}
	
	public function test_adds_end_app() {
		$this->assertFalse($this->builder->frozen);
		$this->builder->run(array());
		$this->assertArrayHasKey(0, $this->builder->stack);
		$this->assertTrue($this->builder->frozen);
	}
	
	public function test_throw_exception_if_already_running() {
		$this->setExpectedException('Exception');
		$this->builder->run(array());
		$this->builder->filter('someMiddleware');
	}
	
	public function test_throw_exception_if_missing_end_app() {
		$this->setExpectedException('Exception');
		$this->builder->filter('someMiddleware');
		$this->builder->call(array());
	}
	


}

?>