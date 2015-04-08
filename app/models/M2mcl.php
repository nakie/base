<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 *
 * Model for table M2mcl
 * 
 * @version 2011.3.10
 * @package Models
 *
 */
class M2mcl extends Application {
  


	public $table_connect = "m2mcl";
	public $keyed_field = "m2mcl_id";
	
	public $prop = array(  "m2mcl_id", "user_id", "admin" );
	
	public function __construct($config=""){
		parent::__construct($config);
	}

	public function post_update_always(){
	
	}

	public function __destruct(){
	
	}
	
}
?>