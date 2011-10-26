# PHP Port of Clockwork

https://github.com/tomykaira/clockwork

## Quickstart

Create clockwork.php

	<?php
	
	require_once('Clockwork.php');
	require_once('Event.php');
	
	use Clockwork\Clockwork; 
	
	$handler = function($job) {
		echo $job.PHP_EOL;
	};
	
	Clockwork::handler($handler);
	
	Clockwork::every('10 seconds', 'frequent.job');
	Clockwork::every('3 minutes', 'less.frequent.job');
	Clockwork::every('1 hour', 'hourly.job');
	
	Clockwork::every('1 hour', 'midhour.job', array('at' => '**:30'));
	Clockwork::every('1 day', 'midnight.job', array('at' => '00:00'));
	
	?>
An run it in another file:

	<?php	
	$clockwork = new Clockwork();
	$clockwork->run();
	?>