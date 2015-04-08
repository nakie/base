<?php
/**
 * Resource object adheres to the Singleton pattern for database connections.
 *
 * Since this is a Singleton class, you must call the static connect() function without
 * instantiating the object (instantiation is not possible).
 *
 * Call the static method like so: Resource::connect($config="");
 *
 * @author Charles Abbott
 * @package Models
 *
 */

class Resource 

{

	private static $instance;

	private $connection;

	private $default_connection;

	private $previous_config = array();





/**
 * Private construct, cannot be instantiated directly.  Call the static connect() method instead.
 *
 */

	private function __construct(){
	

	}

/**
 * Creates a single instance of the Resource class to access the connection resource via the ->connection() public method.
 *
 * @return object
 */

	public static function connect(){

		if(!is_object(self::$instance)){

				$c = __CLASS__;

				self::$instance = new $c;

		}

		

		return self::$instance;

	}

	

/**

 * Connection returns the common database resource used by every model.

 * 

 * Pass an indexed array of database connection settings to set the "username", "password",

 * "database" values.

 *

 * @param array $config

 * @return resource

 */	

	public function connection($config=null){

		if(is_array($config)){//they are passing in a non-standard connection

			if(count(array_diff($config, $this->previous_config)) > 0){

				$this->previous_config = $config;

				$this->connection = mysql_connect($config['server'], $config['username'], $config['password']) or die("error connecting to database.".mysql_error());

				

			}

			return $this->connection;

		}else{//using standard connection from config

			if(!is_resource($this->connection)){

				$this->default_connection = mysql_connect($config['server'], $config['username'], $config['password']) or die("error connecting to database.".mysql_error());

				return $this->default_connection;

			}

			return $this->default_connection;

		}

		

	}

}



?>