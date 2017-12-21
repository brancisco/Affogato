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
	if (!($type == 'app' || $type == 'action' || $type == 'param' ||
		  $type == 'app_class' || $type == 'app_file' || $type == 'view')) {
		return false;
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
		if(!uriValid($uri, 'app')) {
			header("HTTP/1.0 404 Not Found");
			// pass correct error for logging
			errorHandler('1');
			exit();
		}
		$data['app'] = uriTo($uri, 'app');
	}
	else if ($index == 1) {
		if(!uriValid($uri, 'action')) {
			header("HTTP/1.0 404 Not Found");
			// pass correct error for logging
			errorHandler('1');
			exit();
		}
		$data['action'] = $uri;
	}
	else {
		if(!uriValid($uri, 'param')) {
			header("HTTP/1.0 404 Not Found");
			// pass correct error for logging
			errorHandler('1');
			exit();
		}
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