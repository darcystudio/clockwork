<?php

namespace Clockwork;

use Clockwork\Event;

class Clockwork {
	
	protected static $_events = array();
	
	protected static $_handler;
	
	public static function every($period, $job, array $options = array(), $block = null) {
		static::register($period, $job, $block, $options);
	}
	
	public function clear() {
		$this->events = array();
	}
	
	public static function getHandler() {
		if(is_null(static::$_handler) || !is_callable(static::$_handler)) {
			throw new \Exception('No Handler Defined');
		}
		
		return static::$_handler;
	}
	
	public static function handler($block) {
		static::$_handler = $block;
	}
	
	public function log($msg) {
		echo $msg.PHP_EOL;
	}
	
	public function run() {
		$this->log('Starting clock for '.count(static::$_events).' events: ['.implode(', ', static::$_events).']');
		while(true) {
			$this->tick();
			sleep(1);
		}
	}
	
	private static function register($period, $job, $block = null, array $options = array()) {
		if(!is_null($block) && is_callable($block)) {
			$handler = $block;
		} else {
			$handler = static::getHandler();
		}

		$event = new Event($period, $job, $handler, $options);
		static::$_events[] = $event;
	}
	
	public function tick() {
		$t = time();
		$to_run = array();
		foreach(static::$_events as $event) {
			if($event->isTime($t)) {
				$to_run[] = $event;
			}
		}
		
		foreach($to_run as $event) {
			$this->log("Triggering {$event}");
			$event->run($t);
		}
	}
}

?>