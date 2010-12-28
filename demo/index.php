<?php

// declare namespace
namespace Trex;

// load dependencies
require_once('../lib/trex.php');

// create custom request
$request = new Request($_SERVER);

// create router
$router = new Router();

// map out a few URI templates.. this could be done elsewhere, obviously
$router->send('/demo/user')->to('User');
$router->send('/demo/user/{id}')->to('User');

// direct the request to the correct resource
$resource = $router->direct($request);

// run the resource's code and expect a response
//$response = $resource->process($request);

// send back to client
//echo $response->output();

?>