<?php
require ROOT . '/Affogato/AffoApplication.php';
require ROOT . '/Affogato/AffoController.php';

$route = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
$route_parts = explode('/', $_SERVER['REQUEST_URI']);
$custom_routes = json_decode(CUSTOM_ROUTES, true);
$args = array();

// var_dump($route);
// check custom routing (overrides default)
foreach ($custom_routes as $app => $info) {
	if (isset($info['pattern']) && preg_match($info['pattern'], $route, $matches)) {
		array_shift($matches);
		$args = $matches;
		if (isset($info['path'])) {
			include APPS . "/{$info['path']}";
			exit();
		}
		// else if (isset($info['action'])) {
		// 		maybe implement later
		// }
		else {
			include APPS . "/{$app}/{$app}Controller.php";
			$class = "{$app}Controller";
			$application = new $class();
			$application->route("index", $args);
			exit();
		}
	}
}

// check for default routing
if(sizeof($route_parts) == 0) {

}
else if(sizeof($route_parts) == 1) {
	// index
}
else if(sizeof($route_parts) == 2) {

}
else {

}


?>