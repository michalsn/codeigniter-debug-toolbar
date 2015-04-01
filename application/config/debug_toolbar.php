<?php defined('BASEPATH') or die('No direct script access.');

/*
 * If true, the debug toolbar will be displayed
 * If false, it can be overridden by passing ?debug_key=secretkey
 * - Enable the following in config.php
 *   $config['uri_protocol'] = "PATH_INFO";
 *   $config['enable_query_strings'] = TRUE;
 * - Set the SecretKey
 */
$config['render'] = TRUE;

/*
 * Secret Key
 */
$config['debug_key'] = 'cty4pJXbdZyOXsMovRevZZzHy6FZ5E2t';

/*
 * Location of media
 * relative to your site_domain
 */
$config['icon_path'] = 'assets/debug-toolbar/images';
$config['js_path'] = 'assets/debug-toolbar/js';
$config['css_path'] = 'assets/debug-toolbar/css';

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
	'requests'        => TRUE,
	'configs'         => TRUE,
	'sessions'        => TRUE,
	'cookies'         => TRUE,
	'logs'            => TRUE,
  'files'           => TRUE,
	'ajax'            => TRUE
);

