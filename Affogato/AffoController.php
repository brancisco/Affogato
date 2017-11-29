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
	}

	function route($action, $args) {
		if (method_exists($this, $action)) {
			$reflection = new ReflectionMethod($this, $action);
			if($reflection->getNumberOfRequiredParameters() != sizeof($args)) {
				// give 404 error
				// send headers(404)
				echo "ERROR: NOT ENOUGH PARAMETERS";
			}
			else {
				call_user_method_array($action, $this, $args);
				if (!file_exists(APPS . "/{$this->name}/{$action}.php")) {
					// give error no view
					// log as internal error
					// send headers(404)
					echo "ERROR: NO VIEW";
				}
					
				else {
					// found: send headers(200)
					include APPS . "/{$this->name}/{$action}.php";
				}
			}
		}
	}
}
?>