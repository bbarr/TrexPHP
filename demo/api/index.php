<?php

// declare namespace
namespace Trex;

session_start();

// load dependencies
require_once('../../lib/trex.php');

// create custom request
$request = new Request($_SERVER);

// create router
$router = new Router();

// set up the router with a location to scan for resources, and some routes
// this could be done elsewhere, obviously!
$router->scan('resources');
$router->send('/demo/api/user')->to('User');
$router->send('/demo/api/user/{id}')->to('User');

// match request to route, extract params,
// and return an instance of the matched route's resource
$resource = $router->match($request);

// run the resource's code and expect a response
$response = $resource->process($request);

// send back to client
$response->deliver();

?>