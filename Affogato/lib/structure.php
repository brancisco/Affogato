<?php
	function formatActionAsMethod($action) {
		$out = preg_replace_callback('/-(.)/', 
			function($match) {
				return ucfirst($match[1]);
			},
			$action
		);
		return $out;
	}
	function formatActionAsFile($action) {
		$out = preg_replace_callback('/[A-Z]/', 
			function($match) {
				return '-' . lcfirst($match[0]);
			},
			$action
		);
		return $out . '.php';
	}
	function formatUriAsClass($uri) {
		return ucfirst(formatActionAsMethod($uri));
	}

	function defaultRouting($route_array, $data, $index = 0) {
		if(!(sizeof($route_array) > 0)) {
			return $data;
		}
		$cur = array_shift($route_array);
		if($index == 0) {
			$data['app'] = formatUriAsClass($cur);
		}
		else if ($index == 1) {
			$data['action'] = formatActionAsMethod($cur);
		}
		else {
			array_push($data['args'], $cur);
		}
		return defaultRouting($route_array, $data, $index+1);
	}
?>