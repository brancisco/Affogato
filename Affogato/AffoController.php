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
		$this->get_vars = array();
		$this->stylesheets = '';
	}

	function set($key, $value) {
		$this->var[$key] = $value;
	}
	private function setGetVars() {
		$query = explode('&', parse_url($_SERVER['REQUEST_URI'])['query']);
		foreach ($query as $q) {
			$q = explode('=', $q);
			$this->get_vars[$q[0]] = true;
			if (isset($q[1])) {
				$this->get_vars[$q[0]] = $q[1];
			}
		}
	}
	function QGET($search) {
		if ($this->get_vars[$search] !== NULL) {
			return $this->get_vars[$search];
		}
		else {
			return NULL;
		}
	}
	function css($stylesheet = NULL) {
		if (isset($stylesheet)) {
			$stylesheet = CSS_LOC . $stylesheet;
			$link_html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$stylesheet\">\n";
			$this->stylesheets .= $link_html;
			return $link_html;
		}
		else {
			return $this->stylesheets;
		}
	}
	function routeCheck($action, $args) {
		if (!method_exists($this, uriTo($action, 'action'))) {
			header("Status: 404 Not Found");
			errorHandler('1');
			exit();
			die("ERROR: NO MATCING ACTION");
		}
		$reflection = new ReflectionMethod($this, uriTo($action, 'action'));
		if($reflection->getNumberOfRequiredParameters() > sizeof($args) ||
		   $reflection->getNumberOfParameters() < sizeof($args)) {
			header("Status: 404 Not Found");
			errorHandler('1');
			exit();
			die("ERROR: NUM PARAMETERS DO NOT MATCH");
		}
		$file = uriTo($action, 'view');
		if (!file_exists(APPS . "/{$this->name}/{$file}")) {
			header("Status: 404 Not Found");
			errorHandler('1');
			exit();
			die("ERROR: NO VIEW");
		}
	}

	function route($action, $args=array()) {
		$this->routeCheck($action, $args);
		$this->setGetVars();
		header("HTTP/1.1 200 OK");
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