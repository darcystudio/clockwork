<?php

namespace Clockwork;

class Event {
	protected $_at;
	
	protected $_block;

	protected $_period;

	public $job = null;
	
	public $last = null;
	
	public function __construct($period, $job, $block, array $options = array()) {
		$default = array('at' => null);
		$options += $default;
		
		$this->_at = $this->parseAt($options['at']);
		$this->_block = $block;
		
		$this->_period = $this->parsePeriod($period);
		$this->job = $job;
		$this->last = null;
		
		var_dump($this);
	}
	
	public function __toString() {
		return $this->job;
	}
	
	public function isTime($t) {
		$ellapsed_ready = (is_null($this->last) || ($t - $this->last) >= $this->_period);
		$time_ready = (is_null($this->at) || ((date('H', $t) == $this->_at[0]) && date('i', $t) == $this->_at[1]));
		return ($ellapsed_ready && $time_ready);
	}
	
	public function run($t) {
		$this->last = $t;
		call_user_func($this->_block, $this->job);
	}
	
	public function parseAt($at = null) {
		if(is_null($at)) {
			return null;
		}

		switch($at) {
			case preg_match('/^(\d\d):(\d\d)$/', $at, $matches) > 0:
				$hour = (int) $matches[1];
				$min = (int) $matches[2];
				break;
			case preg_match('/^..:(\d\d)$/', $at, $matches) > 0:
				$hour = null;
				$min = (int) $matches[1];
				break;
			default:
				throw new \Exception('Failed to parse $at');
		}
		
		return array($hour,$min);
	}
	
	/**
	 * @return int
	 */
	public function parsePeriod($period) {
		$interval = \DateInterval::createFromDateString($period);
		return ($interval->y * 365 * 24 * 60 * 60) +
			($interval->m * 30 * 24 * 60 * 60) +
			($interval->d * 24 * 60 * 60) +
			($interval->h * 60 *60) +
			($interval->i * 60) +
			$interval->s;
			
			
	}
}

?>