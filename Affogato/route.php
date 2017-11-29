<?php
require ROOT . '/Affogato/AffoApplication.php';
require ROOT . '/Affogato/AffoController.php';

$route = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
$route_parts = explode('/', preg_replace('/\/$/', '', $route));
$custom_routes = json_decode(CUSTOM_ROUTES, true);
$args = array();

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

// default routing
$data = array('app' => '', 'action' => 'index', 'args' => array());
$data = defaultRouting($route_parts, $data);
$file_name = APPS . "/{$data['app']}/{$data['app']}Controller.php";
if (!file_exists($file_name)) {
	echo "ERROR: 404";
	// internal error no class
	// headers(404)
}
else {
	include $file_name;
	$class = "{$data['app']}Controller";
	$application = new $class();
	$application->route($data['action'], $data['args']);
}
exit();
?>