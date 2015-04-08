<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 */

function data( $field ) {
  
  if( isset( $_SESSION[ 'da' ][ "$field" ] ) ) {
    
    $return =  $_SESSION[ 'da' ][ "$field" ] ;    
    //$return = ( !is_object( $return ) ) ? $_SESSION[ 'lda' ][ "$field" ] : $return;
    
  	return $return;  

  } else {
    
    return false;
  }
  
}

function setData( $field, $val=NULL ) {
  
  $val = ( is_object( $val ) ) ? serialize( $val ) : $val;  
	$_SESSION['da']["$field"] = $val;
	return $_SESSION['da']["$field"] ;
  
}

function clearData( $field=NULL ) {
  
  if( $field !== NULL ){
    
    if ( isset( $_SESSION['da']["$field"] ) ){
      
      $val = $_SESSION['da']["$field"];
      unset( $_SESSION['da']["$field"] );
      
    }
      
  } else {
    
    $val = $_SESSION['da'];
    unset( $_SESSION['da'] );    
  }  
  
  return $val;
  
}

?>