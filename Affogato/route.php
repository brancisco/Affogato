<?php
require ROOT . '/Affogato/AffoApplication.php';
require ROOT . '/Affogato/AffoController.php';
$route = parse_url(preg_replace('/^\//', '', $_SERVER['REQUEST_URI']))['path'];
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
$app_file = uriTo($data['app'], 'app_file');
$class    = uriTo($data['app'], 'app_class');
$file_name = APPS . "/{$data['app']}/{$app_file}";
if (!file_exists($file_name)) {
	header("Status: 404 Not Found");
	die("ERROR: NO MATCHING APP FOUND");
}
else {
	include $file_name;
	$application = new $class();
	$application->route($data['action'], $data['args']);
}
?>