<?php 
// Paste bootstrap.php

// setup error reporting
error_reporting(E_ALL);
ini_set('display_errors', TRUE);

// check PHP version
version_compare(PHP_VERSION, '5.3', '<') and exit('Paste requires PHP 5.3 or newer.');

// start benchmark
define('EXECUTION_START', microtime(TRUE));

// system path defaults, should be configured in index.php
// location of index.php, default is one level up
if (! isset($doc_root))
	$doc_root = __DIR__.'/../';

// directory where content files are stored, relative to doc_root
if (! isset($content_path))
	$content_path = 'content';

// directory where templates are stored, relative to doc_root
if (! isset($template_path))
	$template_path = 'templates';

// directory for cache (must be writeable), relative to doc_root
if (! isset($cache_path))
	$cache_path = 'cache';

// set cache lifetime in seconds. 0 or FALSE disables cache
if (! isset($cache_time))
	$cache_time = 0;

// directory where Paste is located
if (! isset($app_path))
	$app_path = __DIR__;

// global paths with trailing slash for convenience
define('DOC_ROOT', realpath($doc_root).'/');
define('APP_PATH', realpath($app_path).'/');
define('CONTENT_PATH', realpath(DOC_ROOT.$content_path).'/');
define('TEMPLATE_PATH', realpath(DOC_ROOT.$template_path).'/');
define('CACHE_PATH', realpath(DOC_ROOT.$cache_path).'/');

// two useful constants for formatting text
define('TAB', "\t"); 
define('EOL', "\n");

// core Pastefolio class
require_once APP_PATH.'libraries/Paste.php';

// register class autoloader
spl_autoload_register(array('Paste', 'autoloader'));

// setup cache directory
Cache::$directory = CACHE_PATH;

// set cache lifetime in seconds. 0 or FALSE disables cache
Cache::$lifetime = $cache_time;

// merge user defined routes over defaults
Paste::$routes = array_merge(array(
	
	// default content controller
	'_default' => function() { 
		
		echo "Called _default route, URI: ".Paste::$uri."<br/>";
		
	}), $routes);

// match routes and execute
Paste::run();

// stop benchmark, get execution time
define('EXECUTION_TIME', number_format(microtime(TRUE) - EXECUTION_START, 4));

// add benchmark time to end of HTML
// echo EOL.EOL.'<!-- Execution Time: '.EXECUTION_TIME.', Included Files: '.count(get_included_files()).' -->';
echo EOL.'<br/>Execution Time: '.EXECUTION_TIME.', Included Files: '.count(get_included_files());
