<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * Model 
 * 
 * @version 8.16.2007
 * @package Models
 *
 */
class Application extends Model
{
	
	public function __construct($config=""){
		parent::__construct($config);
		$_SESSION['obj_test'] = "First";
	}
	
	/**
	 * Takes a query and returns true if one or more rows are fetched.
	 *
	 * @param string $query
	 * @return boolean
	 * @todo turn this into a SELECT COUNT(*) instead of a select
	 */
	public static function query_returns_record($query){
		$result = db2_exec(DB2Resource::connect()->connection(), $query);
		$result_array = db2_fetch_assoc($result);
		if(is_array($result_array)) {
			return true;
		}else{
			return false;
		}
	}
	
	
	public function __destruct(){
	
	}
}
?>