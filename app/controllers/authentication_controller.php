<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * Initial Authentication controller for all controllers
 * 
 * 
 * @author ICS, Nathan
 * @package Controllers
 *
 */
class authentication_controller {
  
	public $controller = FALSE;
	public $action_complete;
	
	public function __construct( $controller ) {
	  
		$this->controller = new_( $controller , "authentication_controller" );
	}
	
	public function authenticated() {
      
		if ( data('id' ) ) {
		  
      return TRUE;
      
		} else {
		  
			return FALSE;
			
		}
	} // - End function authenticated()
	
	/**
	 * Checks ACL level for user for access 
	 * to restricted areas.
	 *
	 * @param string $method
	 * @return BOOL
	 */
	
	public function privileged( $method ) {
	  $has_privilege = false;
	  
	  // look for settings inside controller
  	if ( is_array( $this->controller->privileges[ $method ] ) ) {

  	  foreach ( $this->controller->privileges[ $method ] as $key => $value ) {
  	    if ( $has_privilege === TRUE ){
  	      break;
  	    }
  	    if ( $key == "membership" && $value == 'active' ) {
  	       
  	       if ( $this->active() ) {
  	         
     	       $has_privilege = TRUE;
     	       break;

  	         
  	       } else {
  	         
  	         $has_privilege = FALSE;
  	         
  	       }

  	    } 
	          
        $has_privilege = $this->acl( $key, $value );  
  	    
			}
			
			if ( $has_privilege === FALSE ) {
			  
        $r =  unserialize( $_SESSION[ "mvc" ][ "request" ] ) ;
        
        if ( is_object( $r ) ) {          
          // print  $r->current_request( "get" ) . "1<br /> <br />";
          if ( $r->previous_request( "get" ) ) {
            $urlArr = split( "_", $r->previous_request( "get" ) );
            $url = "/" . $urlArr[ 1 ] . "/" . $urlArr[ 0 ] . "/" . $r->previous_var();

            $r = urlencode( $url );

            if ( !data( 'previous' ) ) {
              
              setdata( 'previous' , $r );
              
            }
            
           }
        }
        
       errors( array(  "Access Denied" => "You are not logged in or do not have access to this page."  ));
			  			  
			}

			return $has_privilege;
  	  
  	} else { //no settings in controller
  	  
  	  //assume anyone logged in has the right to complete the action  	  
			return TRUE;
			
		}
		
	} // - End function privileged()
	
	public function active() {
	  
    $member = new_( 'Membership' );
    
    // look for a record in the membership table 
    // matching member_num. to verify current chamber
    // membership.
    $member->member_num = data( 'mid' );
    
    if ( $member->find() ) {  // member_num record found
      
      $user = new_( 'User' );
      
      // Verify user ID and user accout is active
      $user->user_id = data( "id" );
      $user->active = "Y";
        
      if ( $user->find() ) {  // user found and active.
        //echo "ACTIVE:: TRUE";
        return TRUE;  // user has access        
      }        
    }
    //echo "ACTIVE:: FALSE";
    // all checks failed
    return FALSE;  // user does not have access
	  
	} // - End function active()
	
  public function acl( $key, $val ){

    $acl = new_( 'M2mcl' );
	   
    // Look for acl(m2mcl) record matching user
    $acl->user_id = data( "id" );     
   
    if (  $acl->find() ) {   // acl record found

      // is user flagged for access
      if( $acl->$key == $val ) {  

        return TRUE;	// user has access
      
      }
  	  
  	}
  //	echo "ACL:: FALSE";
  	// all checks failed
  	return FALSE;  // user does not have access
  	
  } // - End function acl()
	
	public function __call( $method, $params ){
		if ( !isset( $this->controller->public ) ) {
			$this->controller->public = array();
		}
		if (
			   ( ( is_array( $this->controller->public ) && in_array( $method , $this->controller->public ) ) || ( isset( $this->controller->private ) && !in_array( $method , $this->controller->private ) ) )
			|| 
			( $this->authenticated() && $this->privileged( $method ) )
		  ) {
				if ( method_exists( $this->controller , $method ) ) {
				  
					$this->controller->$method( $params );	
					$this->action_complete = TRUE;
					
				} else {
				  
					$this->action_complete = FALSE;
					
				}

			} else {
			  
  			$this->action_complete = FALSE;
  			
  		}
  		
		return $this->action_complete;
		
	} // - End function __call()
	
	
} // - End class authentication_controller
?>