<?php
/**
* 
*/
class AffoController extends AffoApplication
{
	function __construct()
	{
		$this->reflection = new ReflectionObject($this);
		$this->name = str_replace('Controller', '', $this->reflection->name);
		$this->var = array();
	}

	function set($key, $value) {
		$this->var[$key] = $value;
	}

	function routeCheck($action, $args) {
		if (!method_exists($this, uriTo($action, 'action'))) {
			// give 404 error
			// send headers(404)
			die("ERROR: NO MATCING ACTION");
		}
		$reflection = new ReflectionMethod($this, uriTo($action, 'action'));
		if($reflection->getNumberOfRequiredParameters() != sizeof($args)) {
			// give 404 error
			// send headers(404)
			die("ERROR: NUM PARAMETERS DO NOT MATCH");
		}
		$file = uriTo($action, 'view');
		if (!file_exists(APPS . "/{$this->name}/{$file}")) {
			// give error no view
			// log as internal error
			// send headers(404)
			die("ERROR: NO VIEW");
		}
	}

	function route($action, $args=array()) {
		$this->routeCheck($action, $args);
		call_user_method_array(uriTo($action, 'action'), $this, $args);
		$file = uriTo($action, 'view');
		$this->viewFile("/{$this->name}/{$file}", $this->var);
		
	}
	function viewFile($d23487534798, $vars) {
		// globalize vars and keep access to $this
		foreach ($vars as $key => $value) {
			$$key = $value;
		}
		unset($key); unset($value); unset($vars);
		include APPS . $d23487534798;
	}
}
?>