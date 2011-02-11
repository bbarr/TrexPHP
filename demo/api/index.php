<?php

require_once('../../lib/trex.php');
require_once('random_middleware.php');


class EndApp {
	public function call($env) {
		return array(200, array('content-type' => 'text/html'), 'some text for the response');
	}
}

$app = new Trex\Builder;

$app->use('ToUpper');
$app->use('YellIt');
$app->use('pretendWeMadeSomething');

$app->run(new EndApp);

$app->call($_SERVER);

?>