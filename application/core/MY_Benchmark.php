<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Benchmark extends CI_Benchmark {
	
	var $checkpoints = array();
	var $lasttime = 0;
	
	function mark($name)
	{
		$time = microtime();
		$this->marker[$name] = $time;

		list($sm, $ss) = explode(' ', $time);
		$currtime = $sm + $ss;
		$this->checkpoints[$name] = array(
			'diff'		=> $this->lasttime > 0 ? $currtime - $this->lasttime : 0,
			'memory'	=> !function_exists('memory_get_usage') ? '0' : memory_get_usage()
		);
		
		$this->lasttime = $currtime;
	}
	
}