<?php

require_once('Clockwork.php');
require_once('Event.php');

use Clockwork\Clockwork;

date_default_timezone_set('America/New_York'); 

$handler = function($job) {
	echo $job.PHP_EOL;
};

Clockwork::handler($handler);

Clockwork::every('10 seconds', 'frequent.job');
Clockwork::every('3 minutes', 'less.frequent.job');
Clockwork::every('1 hour', 'hourly.job');

Clockwork::every('1 day', 'midnight.job', array('at' => '00:00'));


$clockwork = new Clockwork();
$clockwork->run();
?>