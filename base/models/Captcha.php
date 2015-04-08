<?php
/**
 * 
 */

class Captcha {
	
	public $ssl = false;
	
	public $error = "";
	
	public $is_valid = false;	
	
	protected $publickey = "6LelSe0SAAAAAPGdAdwJPpCwPBR8WOOrmehmxtpm";
	protected $privatekey = "6LelSe0SAAAAAPuXArfGcKqLy4J68_8idtaPkHKb";
	
		
	/**
	 * functioni construct
	 * initialize the recaptcha object.
	 * sets SSL true since this site uses a SSL
	 * includes required library for recaptcha
	 *
	 */
	function __construct(){
		//echo dirname( __FILE__ ) . "/../lib/captcha/" . ;
		
		$this->ssl = true;
		
		require_once( dirname( __FILE__ ) . "/../lib/captcha/" .'recaptchalib.php' );
		
	} //-END construct()
	
	/**
	 * function render
	 * generates input element(s) to display
	 * on user ofrm.	 * 
	 *
	 * @return string containing HTML form input to display.
	 */
	function render() {
		
		 return recaptcha_get_html( $this->publickey, $this->error, $this->ssl );
		
	} //- END render()
	
	
	/**
	 * Function validate
	 * Checks user response to confirm
	 * valid entry
	 *
	 * @param string $remote_user		// $_SERVER["REMOTE_ADDR"]
	 * @param string $challenge_field	// $_POST["recaptcha_challenge_field"]
	 * @param string $response_field	// $_POST["recaptcha_response_field"]
	 * @return bool	 $this->is_valid	// $resp->is_valid
	 */
	function validate( $remote_user, $challenge_field, $response_field ) {
		
		// check users answer
		$resp = recaptcha_check_answer ( $this->privatekey, $remote_user, $challenge_field, $response_field );
		
		// build response based on results of validating user input.
		if ( !$resp->is_valid ) {
			
		    // The CAPTCHA was entered incorrectly
		   	// die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			//      "(reCAPTCHA said: " . $resp->error . ")");
		   
		  	$this->is_valid = false;
		  	$this->error = "The reCAPTCHA wasn't entered correctly, try it again. (reCAPTCHA said: " . $resp->error . ")";
	  	
		} else {
			
	  		// The CAPTCHA was entered incorrectly
			$this->is_valid = true;
	    
	  	}
	  
	  	// Only returning the is_valid part, if is_valid = false 
	  	// $this->error will hold any messages.
		return $this->is_valid;
	
	} //-END validate()

} //-END class Captcha{}