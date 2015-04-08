<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 */

class application_controller extends Controller {
  
  public $addy = array ( "adminto"   => "coupon@shoalschamber.com",
                         "adminfrom" => "coupon@shoalschamber.com", 
                         "sendfrom"  => "Shoals Chamber M2M Coupon",
                         "extra"     => "-f shoals@shoalschamber.com"
                        );
                        
  public $fine_print = array( "Limit 1 per customer.",
                              "Limit 1 per visit.",
                              "New customers only.",
                              "No rain checks.",
                              "Cannot be combined with any other offers or coupons.",
                              "Excludes special order items.",
                              "With purchase of an item of equal or greater value."
                           
                            );
/*
	public function privileged( $method ) {
		if ( is_array( $this->privileges ) ) {
			foreach ( $this->privileges[ $method ] as $udkey => $value ) {
				$udlvl = lda(  'udlvl' );
				if( $udlvl[ $udkey ] >= $value ) {
					$has_privilege = TRUE;
				} else {
				  
					$has_privilege = FALSE;
					break;
					
				}
			}
			
			return $has_privilege;
			
		}else{//no settings in controller - so assume anyone logged in has the right to complete the action 
		  
			return TRUE;
			
		}
	}
	*/
}

?>