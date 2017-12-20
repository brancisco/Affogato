<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/routing.php';

function uriTo($uri, $type) {
	if (!($type == 'app' || $type == 'action' || $type == 'param' ||
		  $type == 'app_class' || $type == 'app_file' || $type == 'view')) {
		return $uri;
	}
	global $DEFAULT_ROUTING;
	$route = $DEFAULT_ROUTING[$type];
	$out = preg_replace_callback($route['replace'], 
		$route['with'],
		$uri
	);
	return $out;
}

function uriValid($uri, $type) {
	if (!($type == 'app' || $type == 'action' || $type == 'param')) {
		return $uri;
	}
	global $DEFAULT_ROUTING;
	$route = $DEFAULT_ROUTING[$type];
	return preg_match($route['validate'], $uri);
}

function defaultRouting($route_array, $data, $index = 0) {
	if(!(sizeof($route_array) > 0)) {
		return $data;
	}
	$uri = array_shift($route_array);
	if($index == 0) {
		$data['app'] = uriTo($uri, 'app');
	}
	else if ($index == 1) {
		$data['action'] = $uri;
	}
	else {
		array_push($data['args'], $uri);
	}
	return defaultRouting($route_array, $data, $index+1);
}

function errorHandler($err) {
	if(file_exists(NOT_FOUND)) {
		include NOT_FOUND;
	}
	else {
		include DEFAULT_NOT_FOUND;
	}
}

?>