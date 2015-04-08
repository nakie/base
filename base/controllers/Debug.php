<?php
/**
* The base controller object from which all controllers are instantiated
*
* 
*
*@author Nathan Crews
*@package Base
*/
class Debug
{
	// controll level of debug information in errors displayed to users/screen
	protected $debug_lvl = 0;
	
	// List/array to store errors/error related information.
	public $error = array();
	

	public function __construct(){
		
	}
	
	public function push_error($error){
		
	}

	public function __destruct(){
	
	}
}
?>