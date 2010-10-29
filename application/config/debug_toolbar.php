<?php defined('BASEPATH') or die('No direct script access.');

/* 
 * If true, the debug toolbar will be displayed
 * If false, it can be overridden by passing ?debug_key=secretkey
 * - Enable the following in config.php
 *   $config['uri_protocol'] = "PATH_INFO";
 *   $config['enable_query_strings'] = TRUE;
 * - Set the SecretKey
 */
$config['render'] = FALSE; 

/*
 * Secret Key
 */
$config['debug_key'] = 'secret$key';

/* 
 * Location of media
 * relative to your site_domain
 */
$config['icon_path'] = 'assets/images';
$config['js_path'] = 'assets/js';
$config['css_path'] = 'assets/css';

/* 
 * List config files you would like to exclude
 * from showing in the toolbar (without extension).
 * Alternatively, set to true to stop all 
 * config files from showing.
 */
$config['skip_configs'] = array('database', 'encryption');

/*
 * Log toolbar data to FirePHP
 */
$config['firephp_enabled'] = TRUE;

/* 
 * Enable or disable specific panels
 */
$config['panels'] = array(
	'benchmarks'      => TRUE,
	'database'        => TRUE,
	'vars_and_config' => TRUE,
	'logs'            => TRUE,
        'files'           => TRUE,
	'ajax'            => TRUE
);

/*
 * Toolbar alignment
 * options: right, left, center
 */
$config['align'] = 'center';

