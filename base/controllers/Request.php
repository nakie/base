<?php
/**
* This is the basic request object.  
*
* By storing the request as an object, we can track the sequence of requests and provide
* a clean interface for handling requests along with any errors we may run into.
*
*@author Charles Abbott
*@package Controllers
*/
class Request
{
	protected $request_response = FALSE;
	protected $default_request;
	protected $requests = array();
	protected $request_total = 1;	
	protected $post_controller;
	protected $post_controller_object;
	protected $get_controller;
	protected $get_controller_object;
	protected $no_log = array();
	protected $last_var;

	public function __construct( $_config=array() )	{
		$this->default_request = $_config['default'];
		if(is_array($_config['no_log']))
			$this->no_log = $_config['no_log'];
	}
	
	public function execute_requests(){

		foreach ( $this->requests[ $this->request_total ] as $type => $request ) {

				if( $type == 'default' ) $type = 'get';
				$method = $type . '_' . $request;
				$object = $type . '_controller_object';
        $this->last_var = $_GET["var"];
				if( is_object( $this->$object ) ) {//should be the authentication controller

					if ( $this->$object->$method() !== FALSE ) {
						$this->request_response = TRUE;
					}
				}
		}

		if($this->request_response !== TRUE && !isset($this->requests[$this->request_total]['default'])){
			$this->register_default_request();
			$this->execute_requests();
		}
		if($this->request_response !== TRUE){
			$this->execute_error_request();
		}
	}
	
	public function execute_error_request(){
		echo "Configuration error, site not available";
	}
	
	public function register_default_request(){
			$default_controller_action = explode("/",$this->default_request);
			$this->get_controller = $default_controller_action[1]."_controller";
			
			$request = rtrim(implode('_',array_reverse($default_controller_action)),'_');//default looks like /controller/action
			
			$this->log_request("default", $request);
			$this->register_default_controller();
	}
	
	public function register_default_controller(){
			if(!empty($this->get_controller))
			$this->get_controller_object = new_($this->get_controller);
	}
	
	public function register_post_request(){
		if ( isset( $_POST[ "post" ] ) ) {
			$_POST[ "post" ] = str_replace( "." , "_" , $_POST[ "post" ] );
		    $this->post_controller = substr( $_POST[ "post" ] , strrpos( $_POST[ "post" ] , "_" ) + 1 ) . "_controller";
		} else { // Else added to stop error reporting 12/01/08 :: NBC
			$_POST[ "post" ] = '';
		}
		
		$this->log_request( "post" , $_POST[ "post" ] );
		$this->register_post_controller();	
	}
	
	public function register_post_controller() {
		if( !empty( $this->post_controller ) )
			$this->post_controller_object = new_( $this->post_controller );
	}

	public function register_get_request(){
		if( isset( $_GET[ "controller" ] ) ) {
			$_GET[ "controller" ] = str_replace( "." , "_" , $_GET[ "controller" ] );
			$this->get_controller = $_GET[ "controller" ] . "_controller";
			$request = $_GET[ 'action' ] . '_' . $_GET[ 'controller' ];
		//}else{ // Else added to stop error reporting 12/01/08 :: NBC
		//	$request = '';
		}
		
		$this->log_request( 'get' , $request );
		$this->register_get_controller();
	}
	
	public function register_get_controller(){
		if($this->current_request('post') == $this->current_request('get')){
			$this->get_controller_object = $this->post_controller_object;
		}else{
			if(!empty($this->get_controller))
				$this->get_controller_object = new_($this->get_controller);
		}
	}
	
	public function clean_request(){
		 // if isset added for error checking/notice suppression 12/01/08::NBC
		if(isset($this->requests[$this->request_total])){
			if(is_array($this->requests[$this->request_total])){
				foreach($this->requests[$this->request_total] as $last_request){	
					if(in_array($last_request,$this->no_log)){
						array_pop($this->requests);
						$this->request_total--;
						break;
					}
				}
			}
		}//\\//
		$this->post_controller = null;
		$this->get_controller = null;
		$this->get_controller_object = null;
		$this->post_controller_object = null;
	}
	
	public function log_request($type, $request){
		$this->requests[$this->request_total][$type] = $request;
	}
	
	public function previous_request($type){
		$total = (isset($this->request_total)) ? $this->request_total : 0; // 12/01/08::NBC
		$index = $total - 1; // 12/01/08::NBC
		$index = $this->request_total - 1;
		if($index < 0) $index = 0;
		if(isset($this->requests[$index][$type])){
			return $this->requests[$index][$type];
		}
	}
	
	public function previous_var() {
	  return $this->last_var;
	}
	
	public function current_request($type){
		return $this->requests[$this->request_total][$type];
	}
	
	public function retrieve_object($type){
		$object = $type."_controller_object";
		return $this->$object;
	}
	
	protected function update_request_total(){
		$this->request_total++;
	}
	
	protected function reset_request_response(){
		$this->request_response = FALSE;
	}
	
	public function __sleep(){
		$this->clean_request();
		return array_keys(get_object_vars($this));
	}

	public function __wakeup(){
		$this->update_request_total();
		$this->reset_request_response();
	}
	

	public function __destruct()
	{
		setmvc('request',$this);
	}
}

?>