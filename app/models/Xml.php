<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 *
 * Model for reading a XML Printed
 * 
 * @version 2011.3.10
 * @package Models
 *
 */

class Xml {
  

  private $name = "";
	private $number = ""; 
	private $address = ""; 
	private $zip = ""; 
	private $state = ""; 
	private $city = ""; 
	private $email = ""; 
	private $phone = ""; 
	
	private $count = 0;
	private $ck = TRUE;
	private $array = array();
	private $tag;
	var $parser;

	function Xml() {
	  
    $this->parser = xml_parser_create();	
    xml_set_object($this->parser, &$this);    
    xml_parser_set_option( $this->parser, XML_OPTION_SKIP_WHITE, 1 );
    xml_set_element_handler( $this->parser, "startTag", "endTag" );	
    xml_set_character_data_handler( $this->parser, "contents" );	
	  
	  
	}
	
 	private function contents( $parser, $data ){

 	  switch( $this->tag ) {
	    
 		    case "ORG_X002F_PERSON_X0020_SORT_X0020_NAME":
  	 			$this->name .= $data;
  	 			break;
  	 		
  	 		case "NUMBER":
  	 			$this->number .= $data;	 	
  	 			break;
  
  	 	  case "ORG_X0020_ADDRESS_X0020_LINE_X0020_1":
  	 			$this->address .= $data;	 	
  	 			break;
  	 				 			
  	 		case "ORG_X0020_ZIP_X002F_POSTAL":
  	 			$this->zip .= $data;	 	
  	 			break;
  	 			
  	 		case "ORG_X0020_CITY":
  	 			$this->city .= $data;	 	
  	 			break;
  	 			
  	 		case "ORG_X0020_STATE_X002F_PROVINCE":
  	 			$this->state .= $data;	 	
  	 			break;
  	 			
  	 		case "ORG_X0020_EMAIL":
  	 			$this->email .= $data;	 	
  	 			break;
  	 			
  	 		case "ORG_X0020_PHONE_X0020_1":
  	 			$this->phone .= $data;	 	
  	 			break;
	    }		
	}
	
	function startTag( $parser, $data ) {

	    $this->tag = $data; 	
	}
	
	function endTag( $parser, $data ) {
	
		if ( $data == 'BARBARA' ) {	
			
			$this->array[ $this->count ][ 'name' ]     = trim( $this->name );
			$this->array[ $this->count ][ 'number' ]   = trim( $this->number );		
			$this->array[ $this->count ][ 'address' ]  = trim( $this->address );		
			$this->array[ $this->count ][ 'state' ]    = trim( $this->state );		
			$this->array[ $this->count ][ 'city' ]     = trim( $this->city );		
			$this->array[ $this->count ][ 'email' ]    = trim( $this->email );		
			$this->array[ $this->count ][ 'phone' ]    = trim( $this->phone );		
			$this->array[ $this->count ][ 'zip' ]      = trim( $this->zip );		
			
			 $this->ck = FALSE;	
			 
		 	 $this->name = "";
		 	 $this->number = "";	
		 	 $this->address = "";	
		 	 $this->state = "";	
		 	 $this->city = "";	
		 	 $this->email = "";	
		 	 $this->zip = "";	
		 	 $this->phone = "";	
		 	 
		 	 $this->count++;		 	 
		 	 
		 	 //var_dump($this->array);
		}			
	}
	
  public function parse( $f ) {

    if ( $f[ 'tmp_name' ] ) {	
      	
    	//$file  = $_FILES[ 'file'][ 'tmp_name' ];
    	$file  = $f[ 'tmp_name' ];
    	$filename = $f[ 'name' ];
    	$ext = substr( $filename, strrpos( $filename, '.' ) + 1 );
    	//$smarty->assign( 'ext', $filename );
    	
    	if ( $ext == 'xml' || $ext == 'XML' ) {		
    		

    		$fp = fopen( $file, "r" );	
    		$data = fread( $fp, filesize( $file ) );	
    				
    		if ( !( xml_parse( $this->parser, $data, feof( $fp ) ) ) ) {
    		
    		  errors( array(  "XML Parse Error"  => "Error in XML file on Line: " . xml_get_current_line_number( $this->parser ) ) );
    			//$smarty->assign('check', "Error in XML file on Line: " . xml_get_current_line_number($xml_parser));
    		
    			//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    		} else {
    		
    			xml_parser_free( $this->parser );

    			if ( !$this->ck ) {
    				$con = mysql_connect( "localhost","shoalsch_cpnusr","m@mc0upon" );
    				if ( !$con ) {
    					die( 'Could not connect: ' . mysql_error() );
    				}
    				mysql_select_db( "shoalsch_m2mcoupon", $con );	
    				$query = "TRUNCATE TABLE membership";	
    				mysql_query( $query );
    		
    			//	$string = "INSERT INTO `members` (`member_id`, `mname`) VALUES \n" ;
    				$string = "INSERT INTO `membership` (`member_num`, `org_address`,  `org_city`, `org_state`, `org_zip`, `org_email`, `org_phone`, `org_name` ) VALUES \n" ;
    				for ( $i = 0; $i < count( $this->array ); $i++ ) {	
    					$string .= " ('" . mysql_real_escape_string( $this->array[ $i ][ 'number' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'address' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'city' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'state' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'zip' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'email' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'phone' ] ) . "' , '" . 
    					                   mysql_real_escape_string( $this->array[ $i ][ 'name' ] )  . "'),\n";	
    				}
    				$string = substr( $string, 0, -2 );
    				$string .= ";";

    				
    				//var_dump( $string );
    				mysql_query( $string );	
    					
    				//$smarty->assign('check', "<pre>". $string . "</pre>");
    				//$smarty->assign('check', ">>> Upload Successful <<<");
    			}else{
    					//$smarty->assign('check', "ERROR: Problem with Data in File.");
    			}	
    			fclose( $fp ); 				
    		}
    		//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    	}else{
    		//$smarty->assign('check', "ERROR: Only XML Files are accepted.");
    		//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    	
    	}
    
    } else {
    
    	if ( !$f[ 'error' ] ) {
    		//$smarty->assign( 'check', "ERROR: Upload File not detected please select file and upload again." );
    		//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    	
    	} else {
    		$num = $f[ 'error' ];
    	
    		switch ( $num ) {
    		
    		case '1':
    			//$smarty->assign('check', "ERROR: The uploaded file exceeds the max filesize .");
    			//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    			break;
    			
    		case '2':
    			//$smarty->assign('check', "ERROR: The uploaded file exceeds the max filesize .");
    			//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    			break;
    			
    		case '3':
    			//$smarty->assign('check', "ERROR: The uploaded file exceeds the max filesize .");
    			//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    			break;
    		default: 
    			//$smarty->assign('check', "ERROR: Upload File not detected please select file and upload again.");
    			//redirect_to( 'http://www.shoalschamber.com/shoalsjobs/admin/members/'  );
    			break;	
    		
    		}
    	}
    	
    }
  }
}
  
  ?>