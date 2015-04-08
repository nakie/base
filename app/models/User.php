<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 *
 * Model for table User
 * 
 * @version 2011.3.10
 * @package Models
 *
 */
class User extends Application {
  


	public $table_connect = "user";
	public $keyed_field = "user_id";

	
	public $prop = array(  "user_id", "address", "city", "state", "zip", "phone", "username",
	                       "password", "name", "active", "capproval", "mapproval", "role", 
	                       "member_num"
	                     );
	
	public function __construct($config=""){
		parent::__construct($config);
	}
	
		/**
	 * Enter description here...
	 *
	 * @param string $h String to be hashed
	 * @param string $a Algorithm to be used 
	 * 
	 * @return string Hashed Password
	 */
	public function authenticate( $h , $a = "sha512" ){

	  // check has size for nonce + hash
    if ( strlen( $this->password ) == 256 ) {    
      
      $nonce = substr( $this->password, 0, 128 );

      $hash = $nonce . hash_hmac( $a, $h . $nonce , $this->base_config[ 'salt' ] );

      if ( $hash == $this->password ) {
        
        return true;
        
      }
      
	  } 
	  
    // password is not set or did not match
	  return false;
	  	  
	} // - End function get_hash()
	
		/**
	 * Enter description here...
	 *
	 * @param string $h String to be hashed
	 * @param string $a Algorithm to be used 
	 * 
	 * @return string Hashed Password
	 */
	public function create_pw( $h , $a = "sha512" ){
	  
	  // using unique salt per user plus site wide key to generate hash 
	  // hash( $a , $this->base_config[ 'salt' ] . ":" . $h );
	  
   // if ( !isset( $this->nonce ) ) {
//-     for ( $x = 0; $x < 96; $x++ ) {
       // $this->nonce  .= chr( mt_rand( 0 , 255 ) );
//-       $nonce  .= chr( mt_rand( 0 , 255 ) );
//-      }
//-     $nonce = base64_encode( $nonce );
    

	  //} 
	  
	  $nonce = nonce(96);
	  
	  return $nonce . hash_hmac( $a, $h . $nonce, $this->base_config[ 'salt' ] );
	  
	} // - End function set_hash()
	
	public function __destruct(){
	
	}
	
	
	
}
?>