<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * User controller to handle user requests
 * 
 * @author ICS, Nathan 
 * @package Controllers
 * 
 */
class user_controller extends application_controller {
  
  	public $private = array( "get_update_user", "post_update_user", "post_submit_user",  "get_activate_user", 
  	                         "get_add_user", "get_admin_user", "get_manage_user", "post_activate_user", 
  	                         "post_file_user", "get_file_user", "post_onfile_user"
  	                        );

  	public $privileges = 
            array(  "get_update_user"    => array( 
                                                   "admin"      => "Y",
                          	                       "membership" => "active"
									                              ),
								    "post_update_user"   => array( 
								                                   "admin"      => "Y",
    														                   "membership" => "active",
    														                ),
    							  "post_submit_user"   => array( "admin"      => "Y" ),  														
    							  "get_add_user"       => array( "admin"      => "Y" ),
    							  "get_admin_user"     => array( "admin"      => "Y" ),
    								"get_manage_user"    => array( "admin"      => "Y" ),
    								"get_activate_user"  => array( "admin"      => "Y" ),
    								"post_activate_user" => array( "admin"      => "Y" ),
    								"get_file_user"      => array( "admin"      => "Y" ),
    								"get_csvfile_user"   => array( "admin"      => "Y" ),
    								"post_file_user"     => array( "admin"      => "Y" ),
    								"post_csvfile_user"  => array( "admin"      => "Y" ),
    								"post_onfile_user"   => array( "admin"      => "Y" )
    						 // "get_post_user"      => array( "admin"      => "Y" )
  								);
  								
  								
  /**
   * Index action for Controller. this generates the page 
   * which should display if /user/ or /user/index is called
   * 
   * @access Public
   */
	public function index()	{ 
	  
		$this->view->set_content("main","user_login.php");
		$this->view->render_page();
		
	} // - End function index()
    
	
	/**
	 * Load Login form
	 *
	 * @access Public
	 */
  public function get_login_user() {
    
   //   $r =  unserialize( $_SESSION[ "mvc" ][ "request" ] ) ;
      
     //print $r->current_request("get") . "<br /> <br />";
     //print  $r->previous_request("get") . "<br /> <br />";
     //print  $r->previous_var() . "<br /> <br />";
     
       // var_dump($r);
       // var_dump( $_SESSION);

  
      $this->view->title = "Please Login";
      $this->view->set_content( "main" , "user_login.php" );
     // $this->view->set_content( "alerts" , "notice_alert.php" );//

			$this->view->render_page();
     
    } // - End function get_login_user()

    
  /**
   * process login submission
   * 
   * Todo:  Redirect user to page they were currently on or
   *        if user tried to access a secure area and was forced to login
   *        take the user to the attempted page on successful login.
   * 
   * 
   * @access Public
   */
  public function post_login_user() {
       
  		$user = new_( "User" );  		

      $username = $_POST[ "username" ];
      $pw = $_POST[ "password" ] ;  		
      unset( $_POST[ "password" ] );
  		
      $user->username = $username;

      // make sure a user exists with this username
      if ( $user->find( ) ) {

        if ( $user->mapproval == "Y" && $user->capproval == "Y"  && $user->active == "Y" ) {
       // $user->password = $user->authenticate( $pw );        
     
    		// if user exists matching user name and password
    		// if ( $user->find() ) { 	  
    		  
    		if ( $user->authenticate( $pw ) ) { 
    		  
    		  // clear our un encrypted password from memory asap.  
    		  unset( $pw );
    		    $acl = new_( 'M2mcl' );  		    
    		    $acl->user_id = $user->user_id;
    		    
    		    /**
    		     * If user has ACL record save it to session. Access levels should be 
    		     * double checked when actions are performed
    		     * This is simply used to build Menu Options at the appropriate user level.
    		     */
    		    if ( $acl->find() ) {
  
  		        setData( "admin", $acl->admin );  		     
    		      
    		    }
    		    
    		    $membership = new_( "Membership" );  		    
    		    $membership->member_num = $user->member_num;
    		    
    		    /**
    		     * If there is a member number found in list of current members save a 
    		     * users active status to session. This provides a check to make sure users 
    		     * have a currently active account on the site and a current membership with 
    		     * the chamber.
    		     * 
    		     * Again access levels will be double ched when actions are performed.
    		     * this is just to build user menus.
    		     */
  
            if ( $membership->find() ) {
              
              setData( "active", $user->active );
              
            }
            
            setData( "id", $user->user_id );
            setData( "mid", $user->member_num );
            
            $url =  urldecode( data( "previous" ) );
            
             setdata( 'previous' , FALSE );
    
             if ( $url ) {
               
                forwardto($url);
               
             } 
            
              forwardto( "/coupon/" );
            
          } else {
            
            forwardto( "/user/login" );
            errors( array(  "Login Failed" => "Incorrect password."  ));
             
          }
          
        } else {

          forwardto( "" ); // Sends to the default request along with error below
          errors( array( "Account Not Active" => "This account is not currently active." ) );
          
        }
    		  
  		} else {
  		
        forwardto( "/user/login" );		
        errors( array( "Login Failed" => "This account does not exist." ) );
  		}



    } // - End function post_login_user()
    
    
    /**
     * Logs uers our of the system and destroys their session.
     *
     * @access Public
     */
    public function get_logout_user() {
      
      // Empty they program data area
      clearData();
      
      $this->view->title = "You Have Successfully Logged Out";
  		$this->view->set_content( "main","user_login.php" );
		  $this->view->render_page();
      //$this->index();   // take us home
      
			session_destroy();  
			
			//extra precaution - unset all values in the SESSION - nothing is left!
			unset( $_SESSION );
		  //	session_start();
         
    } // - End function get_logout_user()
    
    /**
     * Load user cration form for user account cration.
     * 
     * @access Public 
     */
    public function get_create_user() {
        
    	$captcha = new_ ( "Captcha" );
   		
   		$this->view->captcha = $captcha->render();
   		 
  		$this->view->set_content( "main","user_create.php" );
  		$this->view->render_page();
      
    } // - End function get_create_user()
    
   public function get_captcha_user() {
            	  
   		$captcha = new_ ( "Captcha" );
   		
   		$this->view->captcha = $captcha->render();
   		//$this->view->captcha = $captcha;
   		//
  		$this->view->set_content( "main","user_create_c.php" );
  		$this->view->render_page();
      
    } // - End function get_create_user()
    
    /**
     * Process user subbmited account creation form
     * 
     * @access Public
     */
    public function post_create_user() {
    		
		$captcha = new_ ( "Captcha" );
		
		$ip = $_SERVER["REMOTE_ADDR"];
		$challenge = $_POST["recaptcha_challenge_field"];
		$response = $_POST["recaptcha_response_field"];
		
		// Validate recaptcha
		if( $captcha->validate( $ip, $challenge, $response ) ){
			
			// if validate returned true the user submission is accepted.
			if ( $_POST[ "terms" ] ) {
	    		
	        	$username = $_POST[ "username" ];
	    		//$password = $_POST[ "password" ];
	    	
	    		$user = new_( "User" );  		
	    		$user->username = $username;
	  
	    		if( $user->username ) {
	      			
	    			// A password must be entered
	      			if ( strlen( $_POST[ "password" ] ) > 0 ) {
	      		  
	      		  		// Password and password confirmation must match
	        			if ( $_POST[ "password" ] == $_POST[ "password2" ] ) {
	         		  
	           				if ( !$user->find() ) {  // check to see if username already exists.
	          		  
	          		  			$user->post_update();
	          		  			$user->password = $user->create_pw( $user->password );  
	        
	                			$user->active    = "N";
	                			$user->mapproval = "N";
	                			$user->capproval = "N";
	          
	                			if( $user->insert() ) {
	                
	                  				$user->find();
	                  
									//$extra = "-f info@hudhomenetwork.net";
									$email = new_( "Email" );
									
									$email->to = $user->username;
									$email->from = $this->addy[ 'adminfrom' ];                
									$email->send_from = $this->addy[ 'sendfrom' ];       
									$email->extra = $this->addy[ 'extra' ];       
									$email->subject = $this->msg->new_account_subject;
									$email->body = $this->msg->new_account_email;   
									$email->send(); 
									
									$adminEmail = new_( "Email" );  
	                  
									$adminEmail->to = $this->addy[ 'adminto' ];
									$adminEmail->from = $this->addy[ 'adminfrom' ];                
									$adminEmail->send_from = $this->addy[ 'sendfrom' ];                
									$adminEmail->extra = $this->addy[ 'extra' ];                
									$adminEmail->subject = $this->msg->new_admin_subject;
									$adminEmail->body = $this->msg->new_admin_email;   
									
									$adminEmail->body = str_replace( "<!--{%LINK}-->", "https://shoalschamber.com/m2m/user/activate/$user->id", $adminEmail->body );
									$adminEmail->body = str_replace( "<!--{%USERNAME}-->", $user->username, $adminEmail->body );
									
									$adminEmail->send();
									
									$this->view->set_content("main","user_success.php");
									$this->view->render_page();
	        
								} else {
									// ERROR ::
									forwardto ( "/user/create" );
									errors( array(  "Account failed"  => "There was an Error creating this uer please try again.") );
								} //- END if( $user->insert)
	          		  
							} else {
								//  ERROR :: User already exists 
								forwardto ( "/user/create" ); 
								errors( array(  "Account failed"  => "This user already exists.") );
							
							} //-END if( !$user->find() ) 		   
	        		
						} else {
							//  ERROR :: Passwords do not match 
							forwardto ( "/user/create" ); 
							errors( array(  "Account failed"  => "Passwords do not match." ) );
						} //-END if ( $_POST[ "password" ] == $_POST[ "password2" ] ) 
	        		
					} else {
						// forwardto ( "/coupon/");
						//  ERROR :: Password not entered.
						forwardto ( "/user/create" );
						errors( array( "Account failed"  => "Passwords cannot be blank." ) );
					} //-END if ( strlen( $_POST[ "password" ] ) > 0 ) 
					
				} else {
					//  ERROR :: Username not entered.
					forwardto ( "/user/create" );
					errors( array( "Account failed"  => "You must provide a Username" ) );
				}
	    		
	  		} else {
		        //  ERROR :: Username not entered.
		        forwardto ( "/user/create" );
		        errors( array( "Account failed"  => "You must Accept the Terms and Conditions to create an account." ) );
	  		
	  		} //-END if ( $_POST[ "terms" ] ) {
									
		} else {
			
			// Human interaction validation failed.  Return user to form with error.    			
			forwardto ( "/user/create" );
    		errors( array( "Submission Failed"  => $captcha->error ) );
			
		} //-END if ( $captcha-validate() )
    		
    		
    	
      
  		
     
  		//forwardto ( "/user/create");
  		 //var_dump($_SESSION );
    } // - End function post_create_user()
    
    
    /**
     * Administrative Submission of a new user account
     * proccessed by post_submit_user()
     * 
     * @access Admin
     */
    public function get_add_user() {
   
      $this->view->set_content( "main", "user_add.php" );
  		$this->view->render_page();
      
    }
    
    
    /**
     * User Account edit screen so user can update their 
     * account information.
     *
     * @access Member => active
     */
    public function get_update_user(){
      
      $user = new_( 'User' );
      
      $user->user_id = data( 'id' );
      
      if ( $user->find() ) {
        
        $this->view->user = $user;
        
      }
      
  		$this->view->set_content( "main" , "user_update.php" );
  		$this->view->render_page();
      
      
    } // - End function get_update_user()
    
    
    /**
     * Proccess user subbmitted form 
     *
     * @access Member => active
     */
    public function post_update_user() {
                 
      //$id = $_POST[ 'user_id' ];      
      
      $user = new_( 'User' );
      
      $user->user_id = $_POST[ 'user_id' ]; 
      
      // $user->post_update();
      
      // confirm ID of user and user being updated match.
      // only admins can update user ID's other than their own.
      if ( data( "id" ) == $user->user_id ) {
        
        $user->post_update();
        
        // if user is changing password create hash
        if( strlen( $user->password ) > 0 ||  strlen( $_POST[ "password2" ] ) > 0 ) {
            
          if ( $user->password == $_POST[ "password2" ] ) {
                        
            if ( strlen( $user->password ) >= 6 ) {    // requre => 6 characters in password
              
      		    $user->password = $user->create_pw( $user->password ); 
              
      		    $user->update();
              forwardto( "/coupon" );  

            } else {
              
        		  // ERROR: user is updating password but passwords do not match 
              errors( array(  "Update failed" => "The password must be atleast 6 Characters." ) );
              forwardto( "/user/update" );
              
            }           
            
          } else {
            
      		  // ERROR: user is updating password but passwords do not match 
            errors( array(  "Update failed" => "Passwords do not match." ) );
            forwardto( "/user/update" );
            
          }                      
          
        } else {  // passwords not being changed update other info
          
          $user->update();
          forwardto( "/coupon" );         
          
        }
      
      } else {
        
  		  // ERROR: User Id does not match current user
        errors( array( "Update failed" => "You can only update the user you are logged in as." ) );
        
      }
      
      //$this->index();
    } // - End function post_update_user()
    
    	
    /**
     * Chamber Administrative user activation process
     *
     * @access Administrator
     */
    public function post_activate_user() {   
       // var_dump($_POST);
      $user = new_( 'User' );
      $user->user_id = $_POST["user_id"];
      
      if ( $user->find() ) {
        
        $user->post_update();
        
         
        /*
         * // No Longer Needed Member On File Approval Removed 4/23/2012 
        $confirm = new_( "Approval" );
        
        $confirm->user_id = $user->user_id;
        $confirm->date = date( "Ymd" );
        $confirm->member_num = $user->member_num;
        
        // Generate random approval code
        $confirm->approval_code();  
         * 
         */

        
        $email = new_( "Email" );
        
        $member = new_( "Membership" );
        $member->post_update();
        $member->member_num =  $user->member_num;

        if ( $member->org_email ) {
          if ( $member->find() ) {  // membership record found
            
            // Store email address this message was sent to.
            $confirm->sent_to = $member->org_email;
            
            // build email to send for approval by member on file
            $email->to = $user->username;
            $email->from = $this->addy[ 'adminfrom' ];                
            $email->send_from = $this->addy[ 'sendfrom' ];
            $email->extra = $this->addy[ 'extra' ];    
               
            // Email sent back to User signing up for account  replaced with
            // Final User Approval Notice rather than the Waiting on Member 
            // on File Approval Notice 4/23/2012
            //$email->subject = $this->msg->member_approval_subject;
            //$email->body = $this->msg->member_approval_email;  
            $email->subject = $this->msg->user_approved_subject;
            $email->body = $this->msg->user_approved_email; 
              
            $email->body = str_replace( "<!--{%USERNAME}-->", $user->username, $email->body );
            $email->body = str_replace( "<!--{%LINK}-->", 
                                        "https://shoalschamber.com/m2m/user/confirm/$confirm->nonce", 
                                        $email->body 
                                      );
                                      
            // Write approval record to be retreived by member   
            // No Longer Needed Member On File Approval Removed 4/23/2012                                  
           // $confirm->insert();
  
            if (  $email->send() ) {      // email sent
                      
              // Not fully activated yet Member needs approval first
              // As of 4/23/2012 The user is now fully activated at this point
              $user->active = "Y"; 
              $user->mapproval = "Y";
              $user->capproval = "Y"; 
              
              $user->update();  // write chamber approval
                        
            } else {
                // ERROR :: Could not Send Account creation notification to user
                errors( array( "Account Notification Failed" => "Verify the user 
                    created the account with a Valid Email address and try again." ) );
            }
  
          } else {
            // ERROR :: The selected Member could not be found in the database
            errors( array( "Member ID Not Found" => "Verify the Member number you entered, 
                            and confirm the Database is updated." ) );
          }
        } else {
            // ERROR :: The selected Member could not be found in the database
            errors( array( "No Email on file" => "This Member does not have a valid email 
                            address on file. Please update the membership information." ) );
          
          
        }
        
      } else {
        // ERROR :: User ID not found
        errors( array( "User ID Not Found" => "Verify the User you are trying to approve." ) );
       
      }

      forwardto("/user/manage");
      
    } // - End function post_activate_user()
    
    /**
     * proccess link with random nonce generated to activate an account
     * by the Member currently on file for a given membership
     * 
     * // -- 04/23/2012 -- This step removed.  New Users will no longer be subject
     * to Member On File Approval.  Once a new user is Chamber Approved that user 
     * can post coupons for the Company assinged to by the Chamber.
     *  -- //
     * 
     * @access public
     */
    public function get_confirm_user() {
      
      if( $_GET["var"] ){
        
        $confirm = new_( "Approval" );
        $confirm->nonce = $_GET["var"];
        
        if ( $confirm->find() ) {
          
          $user = new_( "User" ); 
          $user->user_id = $confirm->user_id;
          
          if ( $user->find() ) {
            
            if ( $user->capproval == "Y" ) {
              
              $user->mapproval = "Y";
              $user->active = "Y";
              
              $membership = new_( "Membership" );
              $membership->member_num = $user->member_num;
              $membership->org_email = $confirm->sent_to;
              
              $membership->find();
              
              $email = new_( "Email" );
              
              $email->to = $user->username;
              $email->from = $this->addy[ 'adminfrom' ];                
              $email->send_from = $this->addy[ 'sendfrom' ];
              $email->extra = $this->addy[ 'extra' ];
                    
              if ( $user->username != $membership->org_email ) {
                
                $email->cc = $membership->org_email;  
                
              }
              
              $email->subject = $this->msg->user_approved_subject;
              $email->body = $this->msg->user_approved_email;   
              $email->body = str_replace( "<!--{%USERNAME}-->", $user->username, $email->body);
              $email->send(); 
              
              $user->update();
              
              $confirm->delete();

              $this->view->user = $user;
             // $this->view->confirm = $confirm;
              $this->view->set_content( "main" , "user_confirm.php" );
              $this->view->render_page();
              
            }
            
          } else {
            forwardto( "/coupon/" );
            // ERROR :: no var provided invalid entry point
            errors( array( "Activation Failed" => "This account was not found, or no longer needs approval." ) );
          
          }
          
        } else {
          forwardto( "/coupon/" );
          // ERROR :: no var provided invalid entry point
          errors( array( "Activation Failed" => "This account was not found, or no longer needs approval." ) );
        
        }
                
      } else {
        forwardto( "/coupon/" );
        // ERROR :: no var provided invalid entry point
        errors( array( "Error" => "I'm sorry we could not process your request" ) );
      
      }
      
    } // - End function get_confirm_user() 
    
    
    /**
     * proccess a user response to account confrimation form.
     * 
     * @see /user/confirm
     * @access public
     */
    public function post_confirm_user() {
      
      $confirm = new_( "Approval" );
      $confirm->post_udpate();
      
      if ( $confirm->find() ) {
        // found 
        
      }
      
    }
    
    /**
     * Loads list of users for Administrator
     *  
     * @access Administrator
     */
    public function get_manage_user() {
      
      $user = new_( 'User' );
      
      $this->view->users = $user->find( array ( "all" => "TRUE" ) );
      $this->view->set_content( "main" , "user_manage.php" );
			$this->view->render_page();
      
    } // - End function get_manage_user()

    
    /**
     * a GET request with user_id to give a direct emailable link to
     * administrators to access user approval and edit.   
     *
     * @access Administrator
     */
    public function get_admin_user(){
      
      if ( isset( $_GET[ 'var' ] ) ) {
        
        $id = $_GET[ 'var' ];
        $user = new_( 'User' );
        $user->user_id = $id;
     
        if ( $user->find() ) {
          
          $this->view->user = $user;
          $this->view->set_content( "main" , "user_admin.php" );
          $this->view->render_page();
          
        } else {
          // ERROR :: 
          // no user ID provided redirect to list of users.
          forwardto( "/user/manage" );
          errors( array( "Access Failed" => "Verify the user you are trying to edit and try again." ) );
          
        }
        
      } else {
        // ERROR :: 
        // no user ID provided redirect to list of users.
        forwardto( "/user/manage" );
         errors( array( "Access Failed" => "There was no user ID provided to edit." ) );
      }
      
    } // - End function get_admin_user()
    
    
    /**
     * Proccess Admin user form update/add submission of user accounts
     *
     * @access Administrator
     */
    public function post_submit_user() {
      
      $user = new_( 'User' );
     // $user->user_id = $_POST[ "user_id" ];
     
      $user->post_update();
      
        // if user is changing password create hash
      if( strlen( $user->password ) > 0 ||  strlen( $_POST[ "password2" ] ) > 0 ) {
        
        if ( $user->password == $_POST[ "password2" ] ) {   // passwords must match

          if ( strlen( $user->password ) >= 6 ) {    // requre => 6 characters in password
          
  		      $user->password = $user->create_pw( $user->password );  // create password hash
  		      
            if ( is_null( $user->user_id ) ) {
      
              $user->insert();
              forwardto("/user/manage");
                                  
            } else {
              
              if ( data( "id" ) != $id ) { 
                $user->update();
                forwardto("/user/manage");
                
              }
      
            }    

          } else {
            
      		  // ERROR: Use a password with atleast 6 characters
            errors( array(  "Update failed" => "The password must be atleast 6 Characters." ) );
            
          }
          
        } else {
          
          $user->password = null;
    		  // ERROR: user is updating password but passwords do not match 
          errors( array(  "Update failed" => "Passwords do not match."  ) );
          
        }     
        
      } else {
     
        if ( is_null( $user->user_id ) ) {
        
          if ( $user->insert() ) {
            forwardto("/user/manage");
          }
                
        } else {
          if ( data( "id" ) != $id ) { 
            if ( $user->update() ) {
              forwardto("/user/manage");
            }
          }
  
        }
                
      }
       
    } // - End function post_submit_user()
    
    
    /**
     * May not be used at all, still working on trash collection/storage
     * architecture
     *
     * @access Administrator
     */
    public function post_delete_user() {
      $user = new_( 'User' );
      $user->user_id = $_POST[ "user_id" ];
     // var_dump($user);
      
      if ( $user->find() ) {
       // $user->post_update();
       // $user->update();
        
        forwardto("/user/manage");
      }      
      
    } // - End function post_delete_user()
    
    /**
     * Test function can be safely removed
     *
     */
    public function get_test_user() {
      
      
      $this->view->set_content( "main" , "user_test.php" );
      $this->view->render_page();
      
    }
    
    /**
     * Test function can be safely removed
     *
     */
    public function post_test_user() {
      
      
      if ( isset ( $_POST[ "pass" ] ) ) {
        $user = new_( 'User' );
        
        $user->password = $_POST [ "pass" ];
         		 
  		// $user->pwhash($user->password);
  		 // var_dump( $user );
  		  $this->view->pass1 = $user->pwhash($user->password);
  		  
  		  //var_dump( $user );
  		  
  		 // $this->view->pass2 = $user->decrypt_prop( $user->password, $key, TRUE );
		  
      }
        $this->view->set_content( "main" , "user_test.php" );
        $this->view->render_page();
      
    }
    
    /**
     * Chamber Activation Form
     *
     */
    public function get_activate_user() {
      
      if ( isset( $_GET[ 'var' ] ) ) {
        
        $id = $_GET[ 'var' ];
        $user = new_( 'User' );
        $user->user_id = $id;
     
        if ( $user->find() ) {
          
          $this->view->user = $user;
          $this->view->set_content( "main" , "user_activate.php" );
          $this->view->render_page();
          
        }
        
      } else {
        // ERROR :: 
        // no user ID provided redirect to list of users.
        forwardto( "/user/manage" ); 
         errors( array( "User Not Found" => "Please Select provide a valid User ID." ) );
      }
      
    }

    /**
     * Display xml file upload form.
     * for membership database update.
     * 
     * @access Admin
     *
     */
    public function get_file_user() {
      
      $this->view->set_content( "main" , "user_file.php" );
      $this->view->render_page();
      
    }
    
    
    /**
     * Display CSV file upload form.
     * for membership database update.
     * 
     * @access Admin
     *
     */
    public function get_csvfile_user() {
      
      $this->view->set_content( "main" , "user_csvfile.php" );
      $this->view->render_page();
      
    }
    
    /**
     * Process XML file upload to update membership
     * database
     * 
     * @access Admin
     *
     */
    public function post_file_user() {
      
      //var_dump( $_FILES );
      if ( $_FILES[ "file" ][ "name" ] ) {
        
        $membership = new_( "Membership" );
        
        $membership->process_member_file( $_FILES[ "file" ] );
        
        $this->view->set_content( "main" , "user_file.php" );
        $this->view->render_page();
        
      } else {
        // ERROR :: no file submitted!
        
      }
      
      
    } //- END function post_file_user()
    
        /**
     * Process CSV file upload to update membership
     * database
     * 
     * @access Admin
     *
     */
    public function post_csvfile_user() {
      
      //var_dump( $_FILES );
      if ( $_FILES[ "file" ][ "name" ] ) {
        
        $membership = new_( "Membership" );
        
        $membership->process_member_csvfile( $_FILES[ "file" ] );
        
        $this->view->set_content( "main" , "user_csvfile.php" );
        $this->view->render_page();
        
      } else {
        // ERROR :: no file submitted!
        
      }
      
      
    } //- END function post_csvfile_user()  
    
    
    
    
    /**
     * Called Via Ajax to check Membership database 
     * for multiple records
     * if multiple records found return list to build checkbox.
     * 
     * @access Admin
     * @see coupon/activate/var
     * @uses Ajax.
     *
     */
    public function post_onfile_user() {
      
      $membership = new_( "Membership" );
      $membership->post_update();
      
      
      if ( $membership->find( array( "all" => "TRUE" ) ) ) {
                
        $emails = array();
        foreach ( $membership->find_all as $count => $member ) {
          
          // make sure email being sent back is a valid email
          // This certainly could be improved upon.  i'm simply
          // making sure no spaces are included in the email
          // to stop DO NOT EMAIL email@domain.com from being returned.
          if ( !strstr ( trim( $member->org_email), " " ) ){
            
          if ( strlen( $member->org_email ) > 3 ) {
            
            if ( !in_array( $member->org_email, $emails ) ) {
             
              $emails[] = $member->org_email;
              
            }
            
          }  
          }
          
        }
        
        print ' { "emails" : [ ';
        
        foreach ( $emails as $count => $email ) {
          if ( $count > 0 ) {
            
            print ', ';
            
          }
         // print ' "' . $count . '" : "' . $member->org_email . '" ';
          print ' "' . $email . '" ';
         
        }
        
        print ' ] }';
        
        
      } else {
      
        print '{         
          "err" : "No record found for this membership number."
        }';
      }
    }
    

} // - End class user_controller