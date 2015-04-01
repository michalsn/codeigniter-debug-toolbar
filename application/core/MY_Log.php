<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Log extends CI_Log {
	
	var $logs = array();
	
	function __construct(){
		parent::__construct();
	}
	
	function write_log($level = 'error', $msg, $php_error = FALSE){
		parent::write_log($level, $msg, $php_error);
		
		//$memory	 = (!function_exists('memory_get_usage')) ? '0' : memory_get_usage();
		$b = load_class('Benchmark');
		$b->mark($msg);
		$this->logs[] = array(date('Y-m-d H:i:s P'), $level, $msg);
	}
	
}
