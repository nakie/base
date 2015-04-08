<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * /base/controllers/Controller.php
 * The base controller object from which all controllers are instantiated
 *
 * 
 *
 *@author ICS, Nathan
 *@package Controllers
 */
class Controller {
	protected  $app          = null;
	protected  $vars         = array();
	protected  $default_view = "default_view";
	public     $view         = null;
	public     $msg          = null;

	public function __construct()	{
	  
		if( isset( $this->default_view ) ) {
		  $this->view = new_( $this->default_view );
		}
		
		$this->msg = new_( "Sysmsg" );
		
		if ( isset( $_SESSION['syserr'] ) ) {		  
		  $this->error( $_SESSION[ 'syserr' ] );	
		  unset( $_SESSION[ 'syserr' ] ); 		  
		}
		
		if ( isset( $_SESSION['sysmsg'] ) ) {		  
		  $this->message( $_SESSION[ 'sysmsg' ] );
		  unset( $_SESSION[ 'sysmsg' ] ); 			  
		}
		
		//$this->view->SyMsg = new_( "Sysmsg" );
	}
	
			  
	public function error( $errs ) {
	
	    $this->view->alerts = $errs;    
	    $this->view->set_content( "alerts" , "notice_alert.php" );

		//$this->view->SyMsg = new_( "Sysmsg" );
	}
	
	public function message( $msg ) {
	
	    $this->view->messages = $msg;    
	    $this->view->set_content( "notice" , "notice_notice.php" );

		//$this->view->SyMsg = new_( "Sysmsg" );
	}


	public function __destruct()	{
	
	}
}
?>
