<?php
$DEFAULT_ROUTING = array(
	'app' => array(
		'validate' => '/^[a-z]+((-[a-z]+)*)?$/',
		'replace' => '/((^.)|-[a-z])/',
		'with'    => function($match) {
			return ucfirst(str_replace('-', '', $match[0]));
		}
	),
	'app_file' => array(
		'validate' => '/^[a-z]+((-[a-z]+)*)?$/',
		'replace' => '/((^.)|-[a-z])|($)/',
		'with'    => function($match) {
			if ($match[0] == '') {
				return 'Controller.php';
			}
			return ucfirst(str_replace('-', '', $match[0]));
		}
	),
	'app_class' => array(
		'validate' => '/^[a-z]+((-[a-z]+)*)?$/',
		'replace' => '/((^.)|-[a-z])|($)/',
		'with'    => function($match) {
			if ($match[0] == '') {
				return 'Controller';
			}
			return ucfirst(str_replace('-', '', $match[0]));
		}
	),
	'action' => array(
		'validate' => '/^[a-z]+((-[a-z]+)*)?$/',
		'replace' => '/-[a-z]/',
		'with'    => function($match) {
			return ucfirst(str_replace('-', '', $match[0]));
		}
	),
	'view' => array(
		'replace' => '/($)/',
		'with'    => function($match) {
			if($match[0] == '') {return '.php';}
			return '-' . lcfirst($match[0]);
		}
	),
	'param' => array(
		'validate' => '/^[A-Za-z0-9-]+$/',
		'replace' => '//',
		'with'    => function($match) { return ''; }
))
?>