<?php
define('ROOT', preg_replace('/\/Affogato$/', '', __DIR__));
define('APPS', ROOT . '/app');

define('CUSTOM_ROUTES', json_encode(array(
	'index' =>
		array('pattern' => '/^$/',
			  'path' => 'index.php'), 
	'Article' =>
		array('pattern' => '/^article-([1-9][0-9]*)$/')
)));


?>
