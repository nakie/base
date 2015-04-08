<?php
/**
* The base view object from which all views are instantiated
*
* View objects are automatically instantiated for each controller.  When inside the 
* controller you can use $this->view to access the view object.  The view object provides
* a simple set of methods for rendering content both static and dynamic.  To send a 
* variable to the view simple set it in the controller by $this->view->variable = "foo".
* 
* View objects also allow an easy way to define default templates as well as promote the 
* decorator OO patern for modifying views.
*
*@author Charles Abbott
*@package Views
*/
class View
{
	protected $app = null;
	protected $template = "default.php";
	protected $rss = "rss.php";
	protected $sections = array();
	protected $vars = array();

	public function __construct($section="", $view="")
	{
		$this->app = $_SESSION["mvc"]["app"];
		if(!empty($section) && !empty($view)){		
			$this->set_content($section, $view);
		}
	}	

/**
 * Prepares a content section with a particular view file.  
 * 
 * When the template is rendered a call inside the template is made to render the section (ie. $this->render_section("main") )
 *
 * @param string $section
 * @param string $view
 * @param int $pos
 */
	public function set_content($section, $view, $pos=null)
	{
		if(isset($this->section[$section])){// 12/1/08::NBC added if (isset) /else for error checking
			if(!is_array($this->section[$section])){
				$this->sections[$section] = array();
			}
		}else{// 12/1/08::NBC
			$this->sections[$section] = array();
		}
		if(isset($pos)){
			$this->sections[$section][$pos] = $view;
		}else{
			array_push($this->sections[$section], $view);
		}
	}
	
/**
 * Generally called from the controller to process a views template and generate a page for output
 *
 */
	public function render_page() {
	  
		$include = FALSE;
		if ( isset( $_SESSION[ "mvc" ][ "runtime_decorators" ]  ) ) {
  		foreach( $_SESSION[ "mvc" ][ "runtime_decorators" ] as $decorator ) {
  		  
  			$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . 
  			        $decorator . "/views/templates/" . 
  			        str_replace( "." , "_" . $decorator . "." , $this->template );
  			        
  			if ( file_exists( $file ) ) {
  				ob_start();
  				include $file;
  				$this->page = ob_get_contents();
  				ob_end_clean();
  				$include = TRUE;
  				break;
  			}
  		}
		}
		
		if ( $include === FALSE ) {
		  if ( count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
  			foreach ( $_SESSION[ 'mvc' ][ 'decorators' ] as $decorator ) {
  			  
  				$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . 
                  $decorator . "/views/templates/" . 
                  str_replace( "." , "_" . $decorator . "." , $this->template );
                  
  				if ( file_exists( $file ) ) {
  					ob_start();
  					include $file;
  					$this->page = ob_get_contents();
  					ob_end_clean();
  					$include = TRUE;
  					break;
  				}
  			}
		  }
			if ( $include === FALSE ) {
			  
	    		$file = dirname( __FILE__ ) . "/../../" . $this->app . 
	    		         "/views/templates/" . $this->template;
	    		         
				if( file_exists( $file ) ) {
					ob_start();
					include $file;
					$this->page = ob_get_contents();
					ob_end_clean();
				}
			}
		}
		
		$this->echo_page();
	}

	public function echo_page()
	{
		echo $this->page;
	}

/**
 * Generally called from the template to process and display a particular section specified by $section.
 *
 * @param string $section
 */
	public function render_content( $section ) { 	
		if( array_key_exists($section, $this->sections ) ){
			ob_start();
			if(is_array($this->sections[$section])){
				foreach($this->sections[$section] as $view){
					$include = FALSE;
					
					if(is_object($view)){
						$view->render_object();
					} else {
						$directory = array_shift( explode( "_" , $view ) );
						$view = substr( $view , strpos( $view , "_" ) + 1 );
						
						if ( isset( $_SESSION[ 'mvc' ][ 'runtime_decorators' ] ) ) {
  						foreach( $_SESSION[ 'mvc' ][ 'runtime_decorators' ] as $decorator ) {
  						  
  							$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . 
  							         $decorator . "/views/" . $directory . "/" . 
  							         str_replace(".","_".$decorator.".",$view);
  							if( file_exists( $file ) ) {
  								include $file;
  								$include = TRUE;
  								break;
  							}
  						}
						}
						if($include === FALSE){
						  if ( count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
  							foreach ( $_SESSION[ "mvc" ][ "decorators" ] as $decorator ) {
  								$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . $decorator . "/views/".$directory."/".str_replace(".","_".$decorator.".",$view);
  								if(file_exists($file)){
  									include $file;
  									$include = TRUE;
  									break;
  								}
  							}
						  }
							if ( $include === FALSE ){ 
								$file = dirname(__FILE__)."/../../".$this->app."/views/".$directory."/".$view;
								include $file;
							}
						}
					}
				}
			}
			$this->section_content = ob_get_contents();
			ob_end_clean();
			$this->echo_section($section);
		}
	}
	
	public function echo_section($section)
	{
		echo $this->section_content;
	}

/**
 * Called from within a view to process and render a reusable partial.  All partials follow
 * the naming convention of _something.extension
 *
 * @param string $partial
 */
	public function render_partial($partial)
	{	
		$directory = array_shift(explode("_",$partial));
		$partial = substr($partial, strpos($partial, "_"));
		$include = FALSE;
		ob_start();
		foreach($_SESSION["mvc"]["runtime_decorators"] as $decorator){
			$file = dirname(__FILE__)."/../../".$this->app."/decorators/".$decorator."/views/".$directory."/".str_replace(".","_".$decorator.".",$partial);
			if(file_exists($file)){
				include $file;
				$include = TRUE;
				break;
			}
		}
		if($include === FALSE){
			foreach($_SESSION["mvc"]["decorators"] as $decorator){
				$file = dirname(__FILE__)."/../../".$this->app."/decorators/".$decorator."/views/".$directory."/".str_replace(".","_".$decorator.".",$partial);
				if(file_exists($file)){
					include $file;
					$include = TRUE;
					break;
				}
			}
			if($include === FALSE){
				$file = dirname(__FILE__)."/../../".$this->app."/views/".$directory."/".$partial;
				if(file_exists($file)){
					include $file;
				}else{
					$this->partial_content = $file." does not exist!";
				}
			}
		}
		$this->partial_content = ob_get_contents();
		ob_end_clean();
		$this->echo_partial();
	}
	
	public function render_feed () {
	  
	  header("Content-Type: application/rss+xml; charset=ISO-8859-1");
	  
		$include = FALSE;
		if ( isset( $_SESSION[ "mvc" ][ "runtime_decorators" ]  ) ) {
  		foreach( $_SESSION[ "mvc" ][ "runtime_decorators" ] as $decorator ) {
  		  
  			$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . 
  			        $decorator . "/views/templates/" . 
  			        str_replace( "." , "_" . $decorator . "." , $this->rss );
  			        
  			if ( file_exists( $file ) ) {
  				ob_start();
  				include $file;
  				$this->page = ob_get_contents();
  				ob_end_clean();
  				$include = TRUE;
  				break;
  			}
  		}
		}
		
		if ( $include === FALSE ) {
		  if ( count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
  			foreach ( $_SESSION[ 'mvc' ][ 'decorators' ] as $decorator ) {
  			  
  				$file = dirname( __FILE__ ) . "/../../" . $this->app . "/decorators/" . 
                  $decorator . "/views/templates/" . 
                  str_replace( "." , "_" . $decorator . "." , $this->rss );
                  
  				if ( file_exists( $file ) ) {
  					ob_start();
  					include $file;
  					$this->page = ob_get_contents();
  					ob_end_clean();
  					$include = TRUE;
  					break;
  				}
  			}
		  }
			if ( $include === FALSE ) {
			  
	    		$file = dirname( __FILE__ ) . "/../../" . $this->app . 
	    		         "/views/templates/" . $this->rss;
	    		         
				if( file_exists( $file ) ) {
					ob_start();
					include $file;
					$this->page = ob_get_contents();
					ob_end_clean();
				}
			}
		}
		
		$this->echo_page();
	  
	}
	
	public function echo_partial()
	{
		echo $this->partial_content;
	}
	
	public function __destruct()
	{
	
	}
}

?>