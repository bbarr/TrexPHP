<?php

require_once('../../lib/trex.php');
require_once('random_middleware.php');

class EndApp {
	public function call($env) {
		return array(200, array('content-type' => 'text/html'), 'some text for the response');
	}
}

$app = new Trex\Builder;
$env = new Trex\Request($_SERVER);

$app->filter('ToUpper');
$app->filter('YellIt');
$app->filter('pretendWeMadeSomething');

$app->run(new EndApp);

$response = $app->call($env);

$response->deliver();

?>