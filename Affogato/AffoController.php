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
		$this->scripts = '';
	}
	/**
	 * set
	 * 
	 * @param $key (string) variable name to be used
	 * @param $value (string) the value to set with the given key as its reference
	 */
	function set($key, $value) {
		$this->var[$key] = $value;
	}
	/**
	 * setGetVars
	 *
	 * @description sets the get variables pulling from the uri
	 *
	 */
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
	/** 
	 * css
	 *
	 * @param css_file (string | NULL) - adds stylesheet at default css location
	 * 		and returns the particular html for that css_file or if stylesheet is
	 *		left NULL, all css tags will be returned as string.
	 */
	function css($css_file = NULL) {
		if (isset($css_file)) {
			$css_file = CSS_LOC . $css_file;
			$link_html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css_file\">\n";
			$this->stylesheets .= $link_html;
			return $link_html;
		}
		else {
			return $this->stylesheets;
		}
	}
	/** 
	 * scripty
	 *
	 * @param javascript_file (string | NULL) - adds script at default js location
	 * 		and returns the particular html for that stylesheet or if javascript_file is
	 *		left NULL, all js tags will be returned as string.
	 */
	function scripty($javascript_file = NULL) {
		if (isset($javascript_file)) {
			$javascript_file = JS_LOC . $javascript_file;
			$script_html = "<script type=\"text/javascript\" src=\"$javascript_file\"></script>\n";
			$this->scripts .= $script_html;
			return $script_html;
		}
		else {
			return $this->scripts;
		}
	}
	function routeCheck($action, $args) {
		if (!method_exists($this, uriTo($action, 'action'))) {
			header("HTTP/1.0 404 Not Found");
			errorHandler('1');
			exit();
			die("ERROR: NO MATCING ACTION");
		}
		$reflection = new ReflectionMethod($this, uriTo($action, 'action'));
		if($reflection->getNumberOfRequiredParameters() > sizeof($args) ||
		   $reflection->getNumberOfParameters() < sizeof($args)) {
			header("HTTP/1.0 404 Not Found");
			errorHandler('1');
			exit();
			die("ERROR: NUM PARAMETERS DO NOT MATCH");
		}
		$file = uriTo($action, 'view');
		if (!file_exists(APPS . "/{$this->name}/{$file}")) {
			header("HTTP/1.0 404 Not Found");
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