<?php

/**
 **  TrexPHP
 **  
 **  @author Brendan Barr brendanbarr.web@gmail.com
 **/

require_once('trex/trex.builder.php');
require_once('trex/trex.request.php');
require_once('trex/trex.response.php');

class Trex {
	
	public static $version = array(0, 1);
	
	public static function require_all($dir) {
		foreach (glob($dir . "/*.php") as $filename) {
		    include $filename;
		}
	}
}

?>