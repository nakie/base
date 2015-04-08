<?php
/**
 * 
 */

class Email {
  
  public $headers = NULL;  
  
  public $from = NULL;  
  public $send_from = NULL;  
  public $to = NULL;
  public $cc = NULL;
  
  public $body = NULL;
  public $subject = NULL;
  
  public $extra = NULL; 
  
  
  public function __construct() {
  
  }
  
  public function send() {
    
  	$this->headers  = 'MIME-Version: 1.0' . "\r\n";
  	$this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  	
  	if ( $this->send_from ) {
  	  
  	  $this->headers .= "From: $this->send_from < $this->from > \r\n";
  	  
  	} else {
  	  
  	   $this->headers .= "From:  $this->from  \r\n";
  	   
  	}
  	
		if( isset( $this->cc ) ){

			$this->headers .= 'Cc: "' . $this->cc . '"\r\n';

		}
  	
    $this->headers .= 'Reply-To: "' . $this->from . '" <' . $this->from . '>' . "\r\n";
    
    $this->headers .= 'Return-Path: <' . $this->from . '>' . "\r\n";    
    
    $this->headers .= "X-Mailer: PHP v" . phpversion() . "\r\n";   
      
    if ( $this->extra ) {      
 
    	if( mail( $this->to , $this->subject, $this->body, $this->headers, $this->extra ) ) {    	  
        return true;        
    	}
    	
    } else {
      
    	if( mail( $this->to , $this->subject, $this->body, $this->headers ) ) {    	  
        return true;        
    	}
      
    }
  	
  	return false;
    
  }
  
}
?>