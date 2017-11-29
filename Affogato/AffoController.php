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
	function route($action, $args=array()) {
		if (!method_exists($this, $action)) {
			// give 404 error
			// send headers(404)
			echo "ERROR: NO MATCING ACTION";
		}
		else {
			$reflection = new ReflectionMethod($this, $action);
			if($reflection->getNumberOfRequiredParameters() != sizeof($args)) {
				// give 404 error
				// send headers(404)
				echo "ERROR: NUM PARAMETERS DO NOT MATCH";
			}
			else {
				call_user_method_array($action, $this, $args);
				$file = formatActionAsFile($action);
				if (!file_exists(APPS . "/{$this->name}/{$file}")) {
					// give error no view
					// log as internal error
					// send headers(404)
					echo "ERROR: NO VIEW";
				}
					
				else {
					// found: send headers(200)
					foreach ($this->var as $key => $value) {
						$$key = $value;
					}
					include APPS . "/{$this->name}/{$file}";
				}
			}
		}

	}
}
?>