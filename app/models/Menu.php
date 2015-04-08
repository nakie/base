<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 *
 * Menu Class to build menu options at an appropate level for the user that is 
 * currently logged in.  
 * 
 * @package Models
 * @subpackage Helpers
 *
 */
class Menu {

	static function link( Array $array ) {
	  
		$returnvar = self::authorized( $array );

		self::build_menu_options( $returnvar[ 0 ], $returnvar[ 1 ] );
	}

	static function authorized( Array $array ) {
		
	  foreach ( $array as $menu_text => $request ) {
	    
			$static_method = strtolower( implode( "_" , explode( " " , $menu_text ) ) );
			$controller_action = explode( "/" , $request );
			$controller = $controller_action[ 0 ] . "_controller";
			$method = "get_" . $controller_action[ 1 ] . "_" . $controller_action[ 0 ];
			$cont = new_( $controller );

			if ( $cont->privileged( $method ) ) {
			  
				if ( method_exists( Menu, $static_method ) ) {

					$array = self::$static_method( $array );

				} 
				
			} else {
			  
				unset( $array[ $menu_text ] );
				
			}
		}
		
		return array( $array, $images );
	}

	static function build_menu_options( $arr , $img="" ) {
		foreach ( $arr as $text => $action_cont ) {
			if ( is_array( $img ) ) {
				$image = array_shift( $img );
				if ( !empty( $image ) ) $image = "<img src='/images" . app( TRUE ) . "/$image' /> ";
			}
			$menu = "<li> <a href='" . app( TRUE ) . "/$action_cont'>$image$text</a> </li>";
			echo $menu;
		}
	}
	
	public function login( $action ) {
	  $id = data( 'id' );
	  //var_dump($id);
	  if ( $id  !== false ){
	   
	    return array();   
	  }
	  return $action;
	}
	
	public function logout( $action ) {
	  $id = data( 'id' );
	  //var_dump($id);
	  if ( $id  !== false ){
	   
	    return $action;   
	  }
	  return array();
	}
	
	public function sign_up( $action ) {
	  $id = data( 'id' );
	  //var_dump($id);
	  if ( $id  !== false ){
	   
	    return array();   
	  }
	  return $action;
	}

}
?>