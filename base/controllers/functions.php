<?php
function mvc($field){
	if(isset($_SESSION['mvc']["$field"] )){ // 12/01/08 :: NBC	added isset to prevent error msg
		if(is_string($_SESSION['mvc']["$field"])){
			/**if(is_string($_SESSION['mvc']["$field"])){
				$return = unserialize($_SESSION['mvc']["$field"]);
			}else{
				$return = $_SESSION['mvc']["$field"];
			} 
			changed if/else statme to handle serialized request object in session
			**/
			if($field == 'request'){ // 12/01/08 :: NBC	
				$return = unserialize($_SESSION['mvc']["$field"]);
			}else{
				$return = $_SESSION['mvc']["$field"];
			} // 12/01/08 :: NBC	
		}else{
			$return = $_SESSION['mvc']["$field"];
		}
		//if(!is_object($return)) {
		//	$return = $_SESSION['mvc']["$field"];
		//}
			return $return;	
	}else{
		return false;
	}
}

function setmvc($field,$value=NULL,$add_to_array=FALSE){
	if(is_object($value)) $value = serialize($value);
	if(!$add_to_array){
		$_SESSION['mvc']["$field"] = $value;
	}else{
		$_SESSION['mvc']["$field"][] = $value;
	}
	return $_SESSION['mvc']["$field"] ;	
}

function __autoload($class_name){
	//base Model cannot be overridden
	//other base models can be overridden
	if ( function_exists( "DOMPDF_autoload" ) ) {
		DOMPDF_autoload( $class_name );
	} else {

		if (  $_SESSION[ 'mvc' ][ 'runtime_decorator_class_names' ]  ) {
		  
		  var_dump($_SESSION);
		  
			if ( !is_array( $_SESSION[ 'mvc' ][ 'runtime_decorator_class_names' ] ) ) {
				$_SESSION[ 'mvc' ][ 'runtime_decorator_class_names' ] = array();
			}
		}else{
			$_SESSION['mvc']['runtime_decorator_class_names'] = array();
		}
		if( !in_array( $class_name, $_SESSION[ 'mvc' ][ 'runtime_decorator_class_names' ] ) ) {
			$_SESSION['mvc']['runtime_loaded_classes'][] = $class_name;
			if($class_name == "Model" || $class_name == "View" || $class_name == "Controller"){
				require_once dirname(__FILE__)."/../models/Model.php";
				require_once dirname(__FILE__)."/../views/View.php";
				require_once dirname(__FILE__)."/../controllers/Controller.php";
			}elseif( strstr( $class_name, "_view" ) ) {
				/**
				 * if it is a view, include all the static view decorators
				 */
				if (count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
				foreach( $_SESSION[ 'mvc' ][ 'decorators' ] as $decorator ){
					$file = dirname( __FILE__ ) . "/../../" . $_SESSION[ "mvc" ][ "app" ] . "/decorators/" . $decorator . "/views/" . $class_name . ".php";
					if(file_exists($file)) require_once $file;
				}
				}
				/**
				 * then include the view itself
				 */
				$file = dirname( __FILE__ ) . "/../../" . $_SESSION[ "mvc" ][ "app" ] . "/views/" . $class_name . ".php";
				if( file_exists( $file ) ) require_once $file;		
			
			} elseif ( strstr( $class_name,"_controller" ) ) {
				/**
				 * if it is a controller, include all the static controller decorators
				 */
				if ( count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
				   foreach( $_SESSION[ 'mvc' ][ 'decorators' ] as $decorator ){
					   $file = dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/decorators/".$decorator."/controllers/".$class_name.".php";
  					if ( file_exists( $file ) ) require_once $file;
				  }
				}
				/**
				 * then include the controller itself
				 */
				$file = dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/controllers/".$class_name.".php";	
				if(file_exists($file)) require_once $file;
			}elseif(file_exists(dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/models/".$class_name.".php")){
				/**
				 * if it is a Model, include all the static model decorators
				 */
				if ( count( $_SESSION[ 'mvc' ][ 'decorators' ] ) ) {
  				foreach( $_SESSION['mvc']['decorators'] as $decorator){
  					$file = dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/decorators/".$decorator."/models/".$class_name.".php";
  					(file_exists($file)) ? require_once $file : NULL; 
  					//change decorator to overright model completly when decorator model present
  					//$difle = (file_exists($file)) ?  $file : NULL;
  				}
				}
				/**
				 * then include the model itself
				 */
				$file = dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/models/".$class_name.".php";
				require_once $file;
				//(is_null($dfile))? require_once $file : require_once $dfile;
			}else{
				$file = dirname(__FILE__)."/../models/".$class_name.".php";
				(file_exists($file)) ? require_once $file : $_SESSION["mvc"]["router_include_errors"][] = $file;
			}
		}
	}
}

/**
 * The proper way to instantiate a new object.
 *
 * @param string $class_name
 * @param mixed $construct
 * @return object
 */
function new_($class_name,$construct=""){

	if( strstr( $class_name, "controller" ) && $construct != "authentication_controller" ){
		$construct = $class_name;
		$class_name = "authentication_controller";
	}
	if( strstr( $class_name,"view" ) ){ 
			$dir = "views/";
			$extending = "View";
		}elseif( strstr( $class_name, "controller" ) ){
			$dir = "controllers/";
			$extending = "Controller";
		}else{
			$dir = "models/";
			$extending = "DB2PModel";
	 }

	// Moved around logic to be preformed rather than initializing var
	// 3/19/09::NBC
/*	if(!isset($_SESSION["mvc"]["decorators"])){ // added 12/05/08::NBC
		$_SESSION["mvc"]["decorators"] = array();
	} 
	if(!isset($_SESSION["mvc"]["runtime_decorators"])){
		$_SESSION["mvc"]["runtime_decorators"]= array();
	}
	// added 12/05/08::NBC
	**/
	$at_least_one_decorator_will_decorate = FALSE;
	/**
	 * Only one static decorator is used for a class
	 */
	if ( isset( $_SESSION[ "mvc" ][ "decorators" ] ) ) {// added 3/19/09::NBC
		foreach ( $_SESSION[ "mvc" ][ "decorators" ] as $decorator ){
			$file = dirname(__FILE__) . "/../../" . $_SESSION["mvc"]["app"] . "/decorators/" . $decorator . "/" . $dir . $class_name . "_" . $decorator . ".php";
  					
			$require = ( file_exists( $file ) ) ? TRUE : FALSE;
			if($require === TRUE){
				$_SESSION['mvc']['runtime_loaded_classes'][] = $class_name."_".$decorator;
				$decorator_chain[] = $decorator;
				require_once $file;
				$at_least_one_decorator_will_decorate = TRUE;
				//break;
			}
		}
	}
	/**
	 * Multiple runtime decorators may be attached to a class
	 * 
	 * The last decorator added during runtime is the class that is instantiated, potentially extending all other decorators up the chain.
	 */
	if(isset($_SESSION["mvc"]["runtime_decorators"])){// added 3/19/09::NBC
	   
		foreach($_SESSION["mvc"]["runtime_decorators"] as $decorator){
			$file = dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/decorators/".$decorator."/".$dir.$class_name."_".$decorator.".php";
			$require = (file_exists($file)) ? TRUE : FALSE;	
			if($require === TRUE && $at_least_one_decorator_will_decorate === FALSE) $at_least_one_decorator_will_decorate = TRUE;
			//starting with the first decorator see if it decorates this object
			//if so include and rewrite the class definition.. should look something like account_controller_ics extends account_controller
			//if this is the second decorator added, its class definition would look like account_controller_ics_two extends account_controller_ics
			if($require === TRUE){
				$decorator_chain[] = $decorator;
				$runtime_decorator_class = $class_name.'_'.implode('_',$decorator_chain);
				$runtime_class = rtrim($class_name.'_'.implode('_',array_diff($decorator_chain,array($decorator))),'_');
				if(!in_array($runtime_decorator_class, $_SESSION['mvc']['runtime_decorator_class_names'])){
					if(!class_exists($runtime_class) && !file_exists(dirname(__FILE__)."/../../".$_SESSION["mvc"]["app"]."/".$dir.$class_name.".php")){
						//this will only happen on the first decorator if the current environment does not currently have a class definition
						//now the first class definition will look something like account_controller_ics extends Controller
						//ie the base environment did not have an account_controller - but the decorator did - so it has to extend the root Controller
						$runtime_class = $extending;
					}
					$new_runtime_decorator_class_definition = file_get_contents($file);
						$search = array('RUNTIME_DECORATOR_CLASS','RUNTIME_CLASS','<?php','?>');
						$replace = array($runtime_decorator_class,$runtime_class,'','');
					$new_runtime_decorator_class_definition = str_replace($search, $replace, $new_runtime_decorator_class_definition);
					eval($new_runtime_decorator_class_definition);
					$_SESSION['mvc']['runtime_decorator_class_names'][] = $runtime_decorator_class;
					$_SESSION['mvc']['runtime_decorator_class_definitions'] .= $new_runtime_decorator_class_definition;
				}
			}
		}
	}
	
	if($at_least_one_decorator_will_decorate === TRUE){
		$class = $class_name.'_'.implode('_',$decorator_chain);
				
		if(!empty($construct)){
			$obj = new $class($construct);
		}else{
			$obj = new $class();
		}
		return $obj;
	}else{
		if(!class_exists($class_name)){
			return FALSE;//could not find the class anywhere!!!
		}else{
			if(!empty($construct)){
				$obj = new $class_name($construct);
			}else{

				$obj = new $class_name();

			}
			return $obj;
		}
	}
}

/**
 * Used to determine the current environment inside a script (generally inside a view).
 *
 * @param boolean $return defaults to false (echo) otherwise returns string
 * @return string
 */
function app($return=FALSE){
    if($return){
        return "/".$_SESSION["mvc"]["app"];
    }else{
        echo "/".$_SESSION["mvc"]["app"];
    }
}

/**
 * Generates a link with ajax request built in.
 *
 * @param array $arr
 * @return string
 */
function ajax_link($arr){
        $div = (isset($arr["div"])) ? $arr["div"] : "nodiv";
        $action = (isset($arr["action"])) ? app(TRUE).$arr["action"] : "/";
	$action = (isset($arr["external"])) ? $arr["external"] : $action;
        $effect = (isset($arr["effect"])) ? $arr["effect"] : "Appear";
        $value = (isset($arr["value"])) ? $arr["value"] : "value";
        $show = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').show();" : "";
        $show_off = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').hide();" : "";
        $ajax_link = "<a href=\"".$arr["href"]."\" class=\"".$arr["class"]."\" title=\"".$arr["title"]."\" onclick=\"".$arr["before"]." $show new Ajax.Updater('".$div."', '".$action.$arr["var"]."', { parameters: { evalScripts: true }}); ".$arr["after"]." $show_off return false;\">".$value."</a>";
    if($arr["return"]){ return $ajax_link; }else{ echo $ajax_link; }
}

/**
 * Generates a form submit button with ajax request built in.
 *
 * @param array $arr
 * @return string
 */
function ajax_submit($arr){
    $div = (isset($arr["div"])) ? $arr["div"] : "nodiv";
    $action = (isset($arr["action"])) ? app(TRUE).$arr["action"] : app(TRUE);
    $effect = (isset($arr["effect"])) ? $arr["effect"] : "Appear";
    $value = (isset($arr["value"])) ? $arr["value"] : "Submit";
    $name = (isset($arr["name"])) ? $arr["name"] : "submit";
    $show = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').show();" : "";
    $show_off = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').hide();" : "";
	$id = (isset($arr["id"])) ? "id=\"".$arr["id"]."\"" : "";
	$type = (isset($arr["type"])) ? $arr["type"] :"submit";
	
	$arr["return"] = (isset($arr["return"])) ? $arr["return"] : false; // 12/01/08 :: NBC
	$arr["before"] = (isset($arr["before"])) ? $arr["before"] : ''; // 12/01/08 :: NBC
	$arr["var"] = (isset($arr["var"])) ? $arr["var"] : ''; // 12/01/08 :: NBC
	
    $ajax_submit = "<input type=\"".$type."\" $id name=\"".$name."\" value=\"".$value."\" onclick=\"".$arr["before"]." $show new Ajax.Updater('".$div."', '".$action.$arr["var"]."', {evalScripts:true, onComplete:function(request){new Effect.".$effect."('".$div."');}, onFailure:function(request){alert('Oops, try again.');}, parameters: Form.serialize(this.form)}); $show_off return false;\">";
    if($arr["return"]){ return $ajax_submit; }else{ echo $ajax_submit; }
}

/**
 * Generates a form submit button using an image with ajax request built in.
 *
 * @param array $arr
 * @return string
 */
function ajax_img($arr){
    $div = (isset($arr["div"])) ? $arr["div"] : "nodiv";
    $action = (isset($arr["action"])) ? app(TRUE).$arr["action"] : app(TRUE);
    $effect = (isset($arr["effect"])) ? $arr["effect"] : "Appear";
    $value = (isset($arr["value"])) ? $arr["value"] : "Submit";
    $name = (isset($arr["name"])) ? $arr["name"] : "submit";
    $show = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').show();" : "";
    $show_off = (isset($arr["spinner"])) ? "$('".$arr["spinner"]."').hide();" : "";
    $ajax_img = "<input title=\"".$arr["title"]."\" alt=\"".$arr["alt"]."\" type=\"image\" src=\"".$arr["src"]."\" name=\"".$name."\" value=\"".$value."\" onclick=\"".$arr["before"]." $show new Ajax.Updater('".$div."', '".$action.$arr["var"]."', {evalScripts:true, onComplete:function(request){new Effect.".$effect."('".$div."');}, onFailure:function(request){alert('Oops, try again.');}, parameters: Form.serialize(this.form)}); $show_off return false;\">";
    if($arr["return"]){ return $ajax_img; }else{ echo $ajax_img; }
}

function oc($class){
	echo "onChange=\"this.className='$class';\"";
}

/**
 * Updates an HTML element with the contents of "text" in the parameter array using javascript.
 *
 * @param array $arr
 * @return null
 */
function flash($arr){
	if(!is_array($arr)){
    	$flash["text"] = str_replace('"','\"',$arr);
    	$flash["ele"] = "template_flash";
	}else{
		$flash["ele"] = (isset($arr["ele"])) ? $arr["ele"] : "template_flash";
	}
    echo '<script type="text/javascript"> $("'.$flash["ele"].'").update("'.$flash["text"].'"); </script>';
}

/**
 * Clears an HTML element using javascript.
 *
 * @param array $arr
 * @return string
 */
function clear($elements){
    if(is_array($elements)){
        foreach($elements as $element){
            echo '<script type="text/javascript"> document.getElementById("$element").innerHTML = ""; </script>';
        }
    }else{
        $element = $elements;
        echo '<script type="text/javascript"> document.getElementById("$element").innerHTML = ""; </script>';
    }
}

function style($elements){
            foreach($elements as $element){
                $ele = $element[0];
                $prop = $element[1];
                $val = $element[2];
                echo '<script type="text/javascript"> document.getElementById("$ele").style.$prop = "$val"; </script>';
        }
}

function errors( $errors ) {
  
  foreach( $errors as $key => $error ) {
    
    if( $error != "" ) {
      
      $_SESSION[ 'syserr' ][ $key ] = $error;
      // setData( 'errors', array ( $key => $error ) );
      
    }
  }
  
/*
    $return = "<ul class=\"error\">";
     foreach( $errors as $key => $error ) {
          if( $error != "" ) {
               $return .= "<li> $error </li>";
          }
          
          $return .= "
<script type='text/javascript'> 
alert('howdy');
  $(\"#$key\").addClass('fix'); 
</script>";
      	   
	
	
      }
    $return .= "</ul>";
    if( !isset( $errors[ "return" ] ) ) {
        echo $return;
    }else{
        return $return;
    }
    */
}

/**
 * Forwards the browser to a particular controller/action using javascript.
 *
 * @param array $arr
 * @return string
 */
function forwardto( $action ) {
  echo "<script language='javascript'> window.location='" . app( TRUE ) . $action . "' </script>";
}

function quick_validations($obj){
	echo "<pre>";
	foreach($obj->prop as $prop){
		echo 'public function validate_'.$prop.'(){ 
				if(!$this->has_value("'.$prop.'")) return "'.$prop.' cannot be empty"; 
			}
			
			';
	}
		echo "</pre>";
}
function quick_prop($obj){
	foreach($obj->prop as $prop){
		echo "\"".$prop."\",";
	}
}

/**
 * Test if string $data is seralized or not 
 * 12/1/08 :: NBC
 *
 * @param string $data
 * @return boolean
 */
function is_serialized($data) {
    return ($data == serialize(false) || @unserialize($data) !== false);
}

function nonce( $num, $base64 = TRUE, $alphaNum = FALSE ) {
  if ( $alphaNum === FALSE ) {
    
    for ( $x = 0; $x < $num; $x++ ) {
      // $this->nonce  .= chr( mt_rand( 0 , 255 ) );
      $nonce  .= chr( mt_rand( 0 , 255 ) );
    }
    
  } else {
    
    $alphaNum  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ( $x = 0; $x < $num; $x++ ) {
      // $this->nonce  .= chr( mt_rand( 0 , 255 ) );
      $nonce  .= $alphaNum[ mt_rand( 0 , 36 ) ];
    }
    
  }
  
  if ( $base64 === TRUE ) {
    return base64_encode( $nonce );
  } else {
    return  $nonce ;
  }
  
}
?>
