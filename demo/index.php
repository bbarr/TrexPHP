<?php

// declare namespace
namespace Trex;

// load dependencies
require_once('../lib/trex.php');

// create custom request
$request = new Request($_SERVER);

// create router
$router = new Router($request);

// find resource controller.. can be chained to check multiple locations
// will work with whatever it finds first
$resource = $router->search('app/controllers');

// run the resource's code and expect a response
$response = $resource->execute($request);

// send back to client
echo $response->output();

?>