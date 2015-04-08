<?php

class Twitter {
  
  private $username = "";
  private $password = "";
  
  public $message = "";
  public $url = "http://twitter.com/statuses/update.xml";
  
	public function __construct( $config="" ) {
	  
	  if ( is_array( $config ) ){
	    
	    $this->username = $config[ "username" ];
	    $this->password = $config[ "password" ];
	    
	  } else {
	    
	    require( dirname( __FILE__ ) . "/../../" . $_SESSION[ "mvc" ][ "app" ] . "/configs/" . $_SESSION[ "mvc" ][ "config" ] . ".php" );
	    
	    if ( isset ( $_config[ "twitter" ] ) ) {
  	    $this->username = $_config[ "twitter" ][ "username" ];
  	    $this->password = $_config[ "twitter" ][ "password" ];
	    }	    
	    
	  }
		
	}
	
	public function message( $msg ){
	  
	  $this->$message = $msg;
	  
	}
	
	public function post( $msg="") {
	  // NEED to change this to oAuth instead of basic authentication.
	  if ( !$msg ) {
	    $msg = $this->message;
	  }
  	$curl_handle = curl_init();
  	curl_setopt($curl_handle, CURLOPT_URL, "$this->url");
  	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
  	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($curl_handle, CURLOPT_POST, 1);
  	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$msg");
  	curl_setopt($curl_handle, CURLOPT_USERPWD, "$this->username:$this->password");
  	
  	$buffer = curl_exec($curl_handle);
  	var_dump($buffer);
  	
  	curl_close($curl_handle);
	  
	}

	
	public function __destruct(){
	
	}
  

/**
  $url = 'http://twitter.com/statuses/update.xml';
	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, "$url");
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_POST, 1);
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
	curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);
	*/
}
	?>