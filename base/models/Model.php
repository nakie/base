<?php
/**
 * Base model for all model extensions ( database centric ).
 * 
 * @author Charles Abbott
 * @package Models
 *
 */

class Model {

	private  $db_connect = false;
	private  $user_connect = false;
	private  $pass_connect = false;
	private  $server_connect = false;
	public   $base_connect = false;
	public   $base_config = array();
	public   $validation_status = null;


	public   $join = array();
	public   $meta = array();
	public   $prop = array();
	public   $obj_prop = array();
	public   $above = array();
	public   $below = array();


	public   $select_join = array();
	public   $result = null;	

	public   $last_query_executed = array();
	public   $last_query_error = array();	

	public   $iv = null;


	public function __construct( $config = "" ) {
		$this->set_config( $config );
		$this->table_connect = ( $this->table_connect ) ? $this->table_connect : $config[ 'table' ];
		$this->base_connect();

		
		if( empty( $this->prop ) ) {		
			$result = $this->safe_query( "SHOW COLUMNS FROM $this->table_connect;" );
			while( $fields = $this->num_query( $result ) ) {
				$this->prop[] = $fields[ 0 ];
			}

		}
		foreach( $this->prop as $prop ) {
			$this->obj_prop[ $prop ] = null;
		}

	}



/**
 * Save takes the current object state and saves the properties that are currently set.
 * 
 * Save first runs the validate() method to verify valid data before saving the current object's
 * state to the database.  To force a partial validation (only validate the properties that have values),
 * pass "partial" => TRUE into the param $array.  
 * 
 * If $this->id is_null the object will prepare an INSERT query 
 * to put a new record into the database with values equal to the current object's state.  If $this->id is
 * set, this method will prepare an UPDATE statement to update the respective record in the database table.
 * 
 *
 * @see validate()
 * @param array $array
 * @return boolean
 */

	public function save($array=""){

		if(is_null($this->validate($array["validate"]))){

			if(is_null($this->id)){

				foreach($this->prop as $value){

				   if($value != "id"){

						$keys .= $value.",";
				      	$values .= "'?',";
					  	$array_values[] = $this->$value;

					}

				}

				$result = $this->safe_query("INSERT INTO $this->table_connect (" . rtrim($keys,",") . ") VALUES(" . rtrim($values,",") . ");", $array_values);
				$this->id = mysql_insert_id();

			}else{

				foreach($this->prop as $value){

					if(!empty($this->$value) || $this->$value === 0 || $this->$value === "0"){ 

						$key_value_pairs .= $value." = '?', ";
						$array_values[] = $this->$value;

					}

				}

				$result = $this->safe_query("UPDATE $this->table_connect SET " . rtrim($key_value_pairs,", ") . " WHERE id = $this->id;", $array_values);

			}

			return $result;

		}else{

			return false;

		}

	}

/**
 * Insert saves a new record in the table with the current property values.
 * 
 * Insert first runs the validate() method to verify valid data before saving the current object's
 * state to the database.  To force a partial validation (only validate the properties that have values),
 * pass "partial" => TRUE into the param $array.   
 *
 * @see validate()
 * @param array $array
 * @return boolean
 */

	public function insert($array=""){

		if(is_null($this->validate($array["validate"]))){

			foreach($this->prop as $value){
			   if($value != $this->get_keyed_field()){
					$keys .= $value.",";
			      	$values .= "'?',";

				  	$array_values[] = $this->$value;

				}

			}

			$result = $this->safe_query("INSERT INTO $this->table_connect (" . rtrim($keys,",") . ") VALUES(" . rtrim($values,",") . ");", $array_values);

			if($result) $this->id = mysql_insert_id();

			return $result;

		}else{

			return false;

		}

	}

	

/**
 * Update takes the current object state and updates any matching record with based on the properties that are currently set.
 * 
 * Update first runs the validate() method to verify valid data before saving the current object's
 * state to the database.  To force a partial validation (only validate the properties that have values),
 * pass "partial" => TRUE into the param $array.   
 *
 * @see validate()
 * @param array $array
 * @return boolean

 */

	public function update($array=""){
		$array["where"] = (isset($array["where"])) ? $array["where"] : $this->get_keyed_field()." = '".$this->get_keyed_field_value()."'";
		if(is_null($this->validate($array["validate"]))){
			foreach($this->prop as $value){
				if($this->has_value($value)){ 
					if($value !== $this->get_keyed_field()){
						$key_value_pairs .= $value." = '?', ";
						$array_values[] = $this->$value;
					}
				}

			}
			$result = $this->safe_query("UPDATE $this->table_connect SET " . rtrim($key_value_pairs,", ") . " WHERE " . $array["where"] . ";", $array_values);
			return $result;

		}else{

			return false;
		}
	}

/**
 * DELETEs a record based on the current object's id.
 *
 * @return boolean
 */
	public function delete(){
		$id = $this->get_keyed_field();
		if(is_null($this->$id)){
			return FALSE;

		}else{
			$result = $this->safe_query("DELETE FROM $this->table_connect WHERE {$this->get_keyed_field()} = '{$this->get_keyed_field_value()}';");
		}

		return $result;

	}
		

/**
 * Updates the current object's properties based on data in the $_POST array.
 * 
 * Post_update() initially calls post_update_always() inside the extending class if it exists.  Post_update_always
 * generally is used to seperate any incoming data for multiple properties or to hook in any other specific class
 * methods for additional data handling.
 * 
 * The post_update() method then checks each property to see if it currently has_value() if it does not, then it sets
 * that property to NULL.  Then properties are updated for each (POST[index] = value) with index matching the object's property.
 * NOTE: if the function "post_update_"+property_name exists in the extending class it will be called instead of simply setting
 * the property equal to the POST value.  Inside the "post_update_"+property_name method the property's value must be set, or 
 * otherwise handled.
 *
 * @return void
 */
  public function post_update(){
    
	 if ( method_exists( $this, "post_update_always" ) ) {
			$this->post_update_always();
		}
	   foreach ( $this->prop as $value ) {
			if ( !$this->has_value( $value ) ) {
				$this->$value = NULL;
			}
			
			if ( isset( $_POST[ $value ] ) ) {
				//in case a property needs special processing instead of normal assignment
				$post_update_method = "post_update_".$value;
				if ( method_exists( $this, $post_update_method ) ) {
					$this->$post_update_method();
				} else {
					$this->$value = $_POST[ $value ];
				}
			}
	   }
	}
	
/**
 * Checks to see if this object's property has some value (not a blank string "" or NULL).
 * 
 * Pass the property name in as a string to receive the boolean value response based on its value.
 *
 * @param string $prop
 * @return boolean
 */

	public function has_value($prop){
		$var = null;
		$var = $this->get_prop($prop);
		if(empty($var) && $var !== 0 && $var !== "0"){
			return FALSE; // it does not have a value
		}else{
			return TRUE; // it does have a value either 0 "0" or any other
		}
	}
	
	public function is_empty($prop){
		$var = NULL;
		$var = $value;
		$var = $this->get_prop($prop);
		if(empty($var) && $var !== 0 && $var !== "0"){
			return TRUE; // it is empty and not 0 or "0"
		}else{
			return FALSE;//it has some value either 0 "0" or any other value.
		}
	}
	
	public function get_keyed_field(){
		if(isset($this->keyed_field)){
			return $this->keyed_field;
		}else{
			return "id";
		}
	}
	
	public function get_keyed_field_value(){
		$field = $this->get_keyed_field();
		return $this->$field;
	}
	

/**
 * This function finds and returns either the current object and all its properties, or an array of
 * objects with the same class and properties matching the values found in the db records
 *
 * Find dynamically generates the SQL necessary to retrieve a record's or set of records' properties as accessible 
 * object properties belonging to the current ($this) object, or as an array ($this->find_all) of objects respectively.
 * Find uses the current object's values for generating the WHERE clause of the SQL statement.  Thus an object with
 * properties named "fname" and "email" that have values will generate an SQL select statement which will search on the 
 * fname and email fields.
 * 
 * Find allows a number of configuration values to be passed in any order within an associative indexed array aptly named
 * $array.  Following is a quick list with brief explanation:
 * 
 *   - "comparison" def("="); pass in the comparison operator [=,!=,>,<,LIKE] etc.
 *   - "conjunction" def("AND"); pass in the conjunction operator [AND,OR,NOT]
 *   - "limit" def("LIMIT 1"); pass in a numerical limit, or all = TRUE to retrieve a certain number of records anything greater than 1 will return the records as an array of objects inside $this->find_all
 *   - "all" def(NULL); by passing "all" => TRUE the limit is not set, effectively allowing all matching records to be returned
 *   - "orderby" def(NULL); pass an SQL phrase for ORDER BY - excluding the "ORDER BY" statement itself (ie. "order_by" => " fname ASC, lname ASC ")
 *   - "fields" def(*); pass a list of specific fields to be retrieved, "id" is included regardless
 *   - "where" def(NULL); use this to pass a specific set of WHERE instructions beyond the autogenerated "WHERE field = value" sets.
 * 
 * The find is constructed based on the non-null object properties, if one record is returned then the current object's properties take on the 
 * record's values.  If multiple records are returned then new objects are created and stored as an array inside $this->find_all
 * 
 * @param array $array
 * @return mixed
 */
	public function find($array=""){
		$array["comparison"] = (isset($array["comparison"])) ? $array["comparison"] : "=";
		$array["conjunction"] = (isset($array["conjunction"])) ? $array["conjunction"] : "AND";
		$array["limit"] = (isset($array["limit"])) ? " LIMIT ".$array["limit"] : " LIMIT 1";
		$array["limit"] = (isset($array["all"])) ? "" : $array["limit"];
		$array["orderby"] = (isset($array["orderby"])) ? " ORDER BY ".$array["orderby"] : "";
 		$array["fields"] = (isset($array["fields"])) ? "id, ".$array["fields"] : "*";
		$array["where"] = (isset($array["where"])) ? $array["where"] : "";
		
		foreach($this->prop as $value){
			if(!is_null($this->$value)){
				$key_value_pairs .= $value." ".$array["comparison"]." '?' ".$array["conjunction"]." ";
				$array_values[] = $this->$value;
			}
		}
		if(is_null($key_value_pairs)){
			$result = $this->safe_query("SELECT ".$array["fields"]." FROM $this->table_connect " . $array["where"] . $array["orderby"] . $array["limit"] . ";");
        }else{
			$result = $this->safe_query("SELECT ".$array["fields"]." FROM $this->table_connect WHERE " . rtrim($key_value_pairs,$array["conjunction"]." ") . $array["where"] . $array["orderby"] . $array["limit"] . ";", $array_values);
		}
        if($array["limit"] == " LIMIT 1"){
        	$result_array = $this->assoc_query($result);
		    if(is_array($result_array)){
				foreach($this->prop as $value){//map the return values to the object properties
					$this->$value = $result_array[$value];
					unset($result_array[$value]);
				}
				$this->join = $result_array[$value];
				return true;
		    }else{
				return false;
		    }
		}else{
			$i = 0;
			$class = get_class($this);
			while($array = $this->assoc_query($result)){
				$return[$i] = new $class($this->base_config);
				foreach($this->prop as $value){//map the return values to the object properties
				   $return[$i]->$value = $array[$value];
				   unset($array[$value]);
				}
				$this->join = $result_array[$value];
				$i++;
			}
			$this->find_all = $return;
			return $return;
		}
	}	
	
	/**
	 * Count provides a simple interface similar to find() that returns only a numerical value representing the number of rows potentially fetched in a select statement.
	 *
	 * @param array $array
	 * @return int
	 */
	public function count($array=""){
		if($_SESSION["mvc"]["dev_output"] == 1) echo "\n<!-- Begin->[".get_class($this)."]->find() -- ".microtime(TRUE)." -->\n";
		$array["comparison"] = (isset($array["comparison"])) ? $array["comparison"] : "=";
		$array["conjunction"] = (isset($array["conjunction"])) ? $array["conjunction"] : "AND";
		$array["limit"] = (isset($array["limit"])) ? " FETCH FIRST ".$array["limit"]." ROWS ONLY" : " ";
		$array["limit"] = (isset($array["all"])) ? "" : $array["limit"];
		$array["orderby"] = (isset($array["orderby"])) ? " ORDER BY ".$array["orderby"] : "";
		$array["where"] = (isset($array["where"])) ? $array["where"] : "";
		$array["join"] = (isset($array["join"])) ? $array["join"] : "";
		$array["as"] = (isset($array["as"])) ? $array["as"] : "";
		
			foreach($this->prop as $value){
				if(!is_null($this->$value)){
					$key_value_pairs .= $value." ".$array["comparison"]." '?' ".$array["conjunction"]." ";
					$array_values[] = $this->$value;
				}
			}
			if(is_null($key_value_pairs)){
				$result = $this->safe_query("SELECT COUNT(*) AS TOT FROM $this->table_connect ".$array["as"].$array["join"] . $array["where"] . $array["orderby"] . $array["limit"] . ";");
	        }else{
				$result = $this->safe_query("SELECT COUNT(*) AS TOT FROM $this->table_connect ".$array["as"].$array["join"]."WHERE " . rtrim($key_value_pairs,$array["conjunction"]." ") . $array["where"] . $array["orderby"] . $array["limit"] . " ;", $array_values);
			}
			$array = $this->assoc_query($result);
			return $array["TOT"];
	}

/**
 * Instantiates an object for each related records above and below it in the relational heirarchy.
 *
 * @param array $option
 * @return array multi-dimensional array ["above"] and ["below"] if $option = TRUE
 * @return void default
 */
	public function related($option=FALSE){
		$return["above"] = $this->above($option);

		$return["below"] = $this->below($option);

		if($option) return $return;

	}
	
/**
 * Instantiates an object for each foreign key in the table (looks above to the related table).
 *
 * @param array $option
 * @return array 
 * @return void default
 */
	public function above($option=FALSE){

		foreach($this->prop as $prop){

			if(strpos($prop,"_id")){

				$class = ucwords(substr($prop,0,strpos($prop,"_id")));
				$obj = new $class;
				$obj->id = $this->$prop;

				$obj->find();
				$return[strtolower($class)] = $obj;

			}

		}

		$this->above = $return;

		if($option) return $return;

	}

/**
 * Instantiates an object for each associated record in related tables that are linked in the extending model.
 * 
 * To link a model - add the table's name to a public/protected property $this->has_one_or_more
 *
 * @param array $option
 * @return array 
 * @return void default
 */
	public function below($option=FALSE){

		$foreign_key = strtolower(get_class($this));
		$foreign_key .= "_id";
		foreach($this->has_one_or_more as $has){

			$class = ucwords($has);

			$obj = new $class;
			$obj->$foreign_key = $this->id;
			$return[strtolower($class)] = $obj->find(array("all" => TRUE));

		}

		$this->below = $return;
		if($option) return $return;

	}
	
/**
 * Calls validation methods in the extending class models based upon property names.  If the
 * return is null the validation passed - anything else is returned to the controller for processing.
 *
 * @param array $array
 * @return null validation passed
 * @return array validation failed, property is the array index - value is the error response
 */

	public function validate($array=""){

	    $array["partial"] = ($array["partial"]) ? TRUE : FALSE;
		foreach($this->prop as $value){

			$validate_method = "validate_".$value;
			if( ($array["partial"] === TRUE && !is_null($this->$value)) || $array["partial"] !== TRUE){

				if(method_exists($this, $validate_method)){

                	$return = $this->$validate_method();
					if(!is_null($return)){ $validation_status[$value] = $return; }

			    }

            }

		}

		$this->validation_status = $validation_status;
		return $validation_status;

	}

/**
 * Simple method to concat any errors together as a string delimited by :
 *
 * @return string
 */

	public function validation_errors(){
		if(is_array($this->validation_status)){
			$errors = implode(" : ", $this->validation_status);
		}else{
			$errors = $this->validation_status;
		}
		return $errors;
	}

/**
 * Executes a query and returns a result resource.
 *
 * @param string $query
 * @param array $values
 * @return resource
 */
	public function safe_query($query, $values=""){

		if(is_array($values)){

		  $array["query"] = str_replace("?", "%s", $query);

		  $array["values"]  = array_map('mysql_real_escape_string', $values);

		  array_unshift($array["values"], $array["query"]);

		  $array["query"] = call_user_func_array('sprintf',$array["values"]);

		}else{

			$array["query"] = $query;

		}
		$this->last_query_executed[] = $array["query"];
		$result = mysql_query($array["query"],$this->base_connect);
		$this->last_query_error[] = mysql_error();

		return $result;
	}

/**
 * Pass a result resource to assoc_query and it will return a record in an associative array. 
 *
 * @param resource $result
 * @return array
 */
	public function assoc_query($result){
		return @mysql_fetch_array($result, MYSQL_ASSOC);
	}

/**
 * Pass a result resource to num_query and it will return a record in a numerically indexed array.
 *
 * @param resource $result
 * @return array
 */
	public function num_query($result){
		return @mysql_fetch_array($result, MYSQL_NUM);
	}

/**
 * Simple interface to encrypt a property or group of properties.
 * 
 * This function takes only one parameter, a multi-dimensional array.  The first array element "prop" should
 * contain an array of strings representing the object properties that will be encrypted.  The second
 * array element "key" contains a single value which is the encryption key that is passed to the encryption method.
 * 
 * Each encrypted property is stored in an instance variable following a simple naming format.  The format is 
 * "encrypt_PROPERTY" - where PROPERTY is the string representation of the object property. 
 *
 * @param array $array
 * @return array indexed by property name, with encrypted value
 */

	public function do_encrypt($array){

	}
	
/**
 * Simple interface to decrypt a property or group of properties.
 * 
 * This function takes only one parameter, a multi-dimensional array.  The first array element "prop" should
 * contain an array of strings representing the object properties that will be decrypted.  The second
 * array element "key" contains a single value which is the encryption key that is passed to the decryption method.
 * 
 * Each decrypted property is stored in an instance variable following a simple naming format.  The format is 
 * "decrypt_PROPERTY" - where PROPERTY is the string representation of the object property. 
 *
 * @param array $array
 * @return array indexed by property name, with decrypted value

 */
	public function do_decrypt($array){

	}

	public function decrypt_prop( $msg, $key, $base64 = false ) {
	  
	  if ( $base64 ) {
	    $msg = base64_decode( $msg );
	  }
	  
	  $iv = substr( $msg, 0, 32);                            // extract iv
	  $mo = strlen( $msg ) - 32;                             // get mac offset
	  $em = substr( $msg, $mo );                             // extract mac
	  $msg = substr( $msg, 32, strlen( $msg ) - 64 );        // extract ciphertext
	  $mac = $this->pbkdf2( $iv . $msg, $key , 1000, 32 );   // create mac
	  var_dump($iv);
	  // authenticate mac
	  if ($em !== $mac ){
	    
	    return $msg;
	  }
	  
	  // initialize buffers
	  if ( mcrypt_generic_init( $td, $k, $iv ) !== 0 ) {
	    return false;
	  }
	  
	  $msg = mdecrypt_generic( $td, $msg );
	  $msg = unserialize( $msg );
	  
	  mcrypt_generic_deinit( $td );
	  mcrypt_module_close( $td );
	  
	  // return decrypted message.
	  return $msg;
	  


	}

   
	public function encrypt_prop( $msg , $key , $base64 = false ) {
	  
    // open cipher module
    if ( !$td = mcrypt_module_open('rijndael-256', '', 'ctr', '' ) ) {
      return false;
    }
    
    $msg = serialize( $msg );
    
    if ( empty( $this->iv ) ) {
      
      // create Vector
      $iv = mcrypt_create_iv( 32 , MCRYPT_RAND );
      
    }
    
    // init Buffers
    if ( mcrypt_generic_init( $td, $key, $iv ) !== 0 ) {
      return false;
    }
    
    $msg = mcrypt_generic( $td, $msg );           // encrypt msg
    $msg = $iv . $msg;                            // prepend IV 
    $mac = $this->pbkdf2( $msg, $k, 1000, 32 );   // Create mac
    $msg .= $mac;                                 // append mac
    
    mcrypt_generic_deinit( $td );                 // clear buffers
    mcrypt_module_close( $td );                   // close cipher module
    
    if ( $base64 ) {
      
      $msg = base64_encode( $msg );  // base64 encode msg
      
    }

    // return msg as iv + cipher text + max
    return $msg;        

	}
		
	/**
	 * PBKDF2 Implementation.
	 * Key derivation function to strech keys.
	 *
	 * @param string $p  password
	 * @param string $s  salt
	 * @param int    $c  iteration count ( 1000 or higher )
	 * @param int    $kl derived key length
	 * @param string $a
	 * 
	 * @return string derived key
	 */
	private function pbkdf2( $p , $s , $c , $kl , $a = 'sha256' ) {

        $hl = strlen( hash( $a , null , true ) ); 
        $kb = ceil( $kl / $hl );              
        $dk = '';                           

        
        for ( $block = 1; $block <= $kb; $block ++ ) {

            
            $ib = $b = hash_hmac( $a, $s . pack( 'N' , $block ), $p , true );

            
            for ( $i = 1; $i < $c; $i ++ )
               
                $ib ^= ( $b = hash_hmac( $a , $b , $p , true ) );

           $dk .= $ib; 
	    }
    	    

        return substr( $dk , 0 , $kl );
		
	}
		
	public function set_config($config=""){

		if(is_array($config)){

			$this->base_config = $config;

			$this->base_config["config_revision"] = 999999;

		}else{

			require(dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/configs/".$_SESSION["mvc"]["config"].".php");

			$this->base_config = $_config;
		}
	}
	
	private function base_connect(){
			$this->base_connect = Resource::connect($this->base_config)->connection($this->base_config);
			mysql_select_db($this->base_config["database"] , $this->base_connect);
	}

	public function check_config($_config=array()){
		if($this->base_config !== $_config){
			$this->base_config = $_config;
		}
	}

	public function __sleep(){
		   return array_keys(get_object_vars($this));
	}

	public function __wakeup(){
		   $this->base_connect();		
	}

	public function set_prop($prop, $value){
		return $this->obj_prop[$prop]['value'] = $value;
	}
	
	public function get_prop($prop){
		return $this->obj_prop[$prop]['value'];
	}

	public function __get($prop){
		$method = "get_$prop";
		if(method_exists($this, $method)){
			$this->$method();
		}else{
			if(array_key_exists($prop, $this->obj_prop)){
				return $this->obj_prop[$prop]['value'];
			}else{
				return $this->$prop;
			}
		}
	}
	
	public function __set($prop, $value){
		$method = "set_$prop";
		if(method_exists($this, $method)){
			$this->$method($value);
		}else{
			
			if(array_key_exists($prop, $this->obj_prop)){
				$this->obj_prop[$prop]['value'] = $value;
			}else{
				$this->$prop = $value;
			}
		}
	}
	
	public function __destruct(){

	}
}
?>
