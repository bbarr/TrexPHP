<?php

// load trex libraries
require_once('../lib/trex.php');

// load middleware
Trex::require_all('middleware');

// load app
Trex::require_all('apps');

$app = new Trex\Builder;
$request = Trex\Request::create($_SERVER);

$app->filter('JsonP');

$app->run(new Base);

$app->call($request->env);

?>