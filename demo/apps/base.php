<?php

class Base {
	
	public function call($env) {
		return array(200, array('Content-Type' => 'text/json'), array("{'a' : 'b'}"));
	}
}
?>