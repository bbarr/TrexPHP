<?php

/**
 **  Pack - HTTP Interface
 **  
 **  @author Brendan Barr brendanbarr.web@gmail.com
 **/

require_once('pack/pack.builder.php');
require_once('pack/pack.request.php');
require_once('pack/pack.response.php');

class Pack {
	public static $version = array(0, 1);
	public static function build($fn) {
		$builder = new Pack\Builder;
		$request = new Pack\Request($_SERVER);
		$fn($builder);
		$builder->call($request->env);
	}
}

?>