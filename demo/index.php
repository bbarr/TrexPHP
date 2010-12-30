<?php

// declare namespace
namespace Trex;

// load dependencies
require_once('../lib/trex.php');

// create custom request
$request = new Request($_SERVER);

// create router
$router = new Router();

// set up the router with a location to scan for resources, and some routes
// this could be done elsewhere, obviously!
$router->scan('app/resources');
$router->send('/demo/user')->to('User');
$router->send('/demo/user/{id}')->to('User');
$router->send('/demo/story/{story_id}/user/{id}')->to('User');

// match request to route, extract params,
// and return an instance of the matched route's resource
$resource = $router->match($request);

// run the resource's code and expect a response
$response = $resource->process($request);

// send back to client
$response->deliver();

?>