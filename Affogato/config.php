<?php
define('ROOT', preg_replace('/\/Affogato$/', '', __DIR__));
define('APPS', ROOT . '/app');
define('DEFAULT_NOT_FOUND', ROOT . '/Affogato/lib/404.php');
// define('NOT_FOUND', APPS . '/404.php')
define('CSS_LOC', '/resources/css/');


define('CUSTOM_ROUTES', json_encode(array(
	'index' => array(
		'pattern' => '/^$/',
		'path' => 'index.php'
	),
	'Article' => array(
		'pattern' => '/^article-([1-9][0-9]*)$/'
	)
)));


?>
