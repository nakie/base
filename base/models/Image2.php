<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * 
 * /base/models/Image.php
 * Class to handle dynamic image resizing and
 * cache resized images
 * 
 * @author ICS, Nathan
 * 
**/

/**
 * Usage
 * 
 * 
 *    $thumbHeight = 75;
    	$thumbWidth = 100;
    	
    	$bigHeight = 365;
    	$bigWidth = 486;
    	$imgQuality = 800;
    	
 * 		$thumbImg->height = $thumbHeight;
			$thumbImg->width = $thumbWidth;
			$thumbImg->quality = $imgQuality;
			
			$img_thumbs .='<a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="';
			$img_thumbs .= "MM_swapImage('target','','.". $bigLink ."',1)";
			$img_thumbs .= '"><img src=".'.$thumbImg->renderImg( $files[$pic] )
 *
 */

class Image2 {
  
	//Properties
	private $cacheSize = 250;	// number of files to store before clearing cache
	private $cacheClear = 5;	// maximum number of files to delete on each cache clear
	private $version = 0.01; 	// version number (to force a cache refresh
	private $imgDir;
	private $img;				      // image file to turn into a thumbnail
	private $lastModified;		// last modified time (for caching)	
	private $cache_dir;	
	private $canvas;
	
	private $width; 			    // Width
	private $height;			    // Height
	private $zoomCrop = 1;		// Zoom Crop ( values 0 or 1)
	private $quality = 75;		//  Quality
	private $path = "";       // path to public html folder
	
	private $imageFilters = array(
                            	    "1"  => array( IMG_FILTER_NEGATE, 0 ),
                            	    "2"  => array( IMG_FILTER_GRAYSCALE, 0 ),
                            	    "3"  => array( IMG_FILTER_BRIGHTNESS, 1 ),
                            	    "4"  => array( IMG_FILTER_CONTRAST, 1 ),
                            	    "5"  => array( IMG_FILTER_COLORIZE, 4 ),
                            	    "6"  => array( IMG_FILTER_EDGEDETECT, 0 ),
                            	    "7"  => array( IMG_FILTER_EMBOSS, 0 ),
                            	    "8"  => array( IMG_FILTER_GAUSSIAN_BLUR, 0 ),
                            	    "9"  => array( IMG_FILTER_SELECTIVE_BLUR, 0 ),
                            	    "10" => array( IMG_FILTER_MEAN_REMOVAL, 0 ),
                            	    "11" => array( IMG_FILTER_SMOOTH, 0 ),
                            	);
	
	// Magic Getter and Setter functions
	public function __get( $key ) {
	  
		return $this->$key;
		
	}
	
	public function __set( $key , $val ) {
		
		$this->$key = $val;
	}
	
	public function __construct(  $config = "" ) {
	  
	  $this->set_config( $config );
		$this->path = dirname( __FILE__ ) . "/../../public_html/" ;
		
		if ( $this->imgDir == "" ) {
			
			$this->displayError( "no image directory specified" );
			
		} else {
		
			// set path to cache directory (default is ./cache)
			// this can be changed to a different location			
			if( $this->cache_dir == "") {
			  
			  $this->cache_dir = $this->imgDir . "cache";	
			  
			} 
		}					
	}
	
	public function __destruct() {
	  
	    // remove image from memory
	  	if ( $this->canvas != null ) {
	    	
	  	  imagedestroy( $this->canvas );
	    	
	  	}
	}
	
	
	public function set_config( $config = "" ){

		if ( is_array( $config ) ) {
		
		  //$this->thumbHeight = $config[ 'thumbHeight' ];
    	//$this->thumbWidth  = $config[ 'thumbWidth' ];
    	
    	//$this->bigHeight   = $config[ 'bigHeight' ];
    	//$this->bigWidth    = $config[ 'bigWidth' ];
    	//$this->imgQuality  = $config[ 'imgQuality' ];
    	//$this->imgQuality  = $config[ 'imgQuality' ];
    	$this->imgDir      = $config[ 'imgDir' ];
    	
		} else {

			require( dirname( __FILE__ ) . "/../../" . $_SESSION[ "mvc" ][ "app" ] . "/configs/" . $_SESSION[ "mvc" ][ "config" ] . ".php" );

			//$this->base_config = $_config;
			
			if ( isset( $_config[ 'image' ] ) ) {
			  
  		 // $this->thumbHeight = $_config[ 'image' ][ 'thumbHeight' ];
      	//$this->thumbWidth  = $_config[ 'image' ][ 'thumbWidth' ];
      	
      	//$this->bigHeight   = $_config[ 'image' ][ 'bigHeight' ];
      	//$this->bigWidth    = $_config[ 'image' ][ 'bigWidth' ];
      	//$this->imgQuality  = $_config[ 'image' ][ 'imgQuality' ];
      	//$this->imgQuality  = $_config[ 'image' ][ 'imgQuality' ];
      	$this->imgDir      = $_config[ 'image' ][ 'imgDir' ];		  
  	    $this->cache_dir   = $_config[ 'image' ][ 'cacheDir' ];		  
			  
			}
		}
		
	//	var_dump( $this );
	}
	
	public function renderImg( $img = "", $filters = "" ) {		

		$returnImage = '';
		
		if ( $img == "" || strlen( $img ) <= 3 ) {
			
			$this->displayError( "no image specified" );
		    
		} else {
			
			//$this->src = $this->imgDir . "/" . $img;
			$this->img = $img;
			$src =  $this->imgDir . $img;
									
		}
		 
		if ( !file_exists( $this->path . $src ) ) {

			 $this->displayError( "Specified Image could not be found." );
			
		}

		// last modified time (for caching)
		$this->lastModified = filemtime( $this->path . $src );
		
		if ( $this->width == 0 && $this->height == 0 ) {
		    $this->width = 100;
		    $this->height = 100;
		}
				
		// set path to cache directory (default is ./cache)
		// this can be changed to a different location
		//$cache_dir = './cache';
		
		// get mime type of src
		$mime_type = $this->mime_type( $src );
		
		// check to see if this image is in the cache already
		$returnImage = $this->check_cache( $this->cache_dir, $mime_type );
//var_dump($returnImage);
		if( $returnImage ){

			return $returnImage;
		}
		
		// if not in cache then clear some space and generate a new file
		$this->cleanCache();
		
		//ini_set('memory_limit', "30M");
		
		// make sure that the src is gif/jpg/png
		if ( !$this->valid_src_mime_type( $mime_type ) ) {
		  
		    displayError( "Invalid src mime type: " .$mime_type );
		    
		}
		
		// check to see if GD function exist
		if ( !function_exists( 'imagecreatetruecolor') ) {
		    displayError( "GD Library Error: imagecreatetruecolor does not exist" );
		}
			
		if ( strlen( $this->path . $src ) && file_exists( $this->path . $src ) ) {
		
		    // open the existing image
		    $image = $this->open_image( $mime_type, $this->path . $src );
		    if ( $image === false ) {
		        $this->displayError( 'Unable to open image : ' . $this->path . $src );
		    }
        
		    // Get original width and height
		    $width = imagesx( $image );
		    $height = imagesy( $image );
		    
		    // don't allow new width or height to be greater than the original
		    if ( $this->width > $width ) {
		        $this->width = $width;
		    }
		    if ( $this->height > $height ) {
		        $this->height = $height;
		    }
		
		    // generate new w/h if not provided
		    if ( $this->width && !$this->height ) {
		       
		        $this->height = $height * ( $this->width / $width );
		       
		    } elseif ( $this->height && !$this->width ) {
		       
		        $this->width = $width * ( $this->height / $height );
		       
		    } elseif ( !$this->width && !$this->height ) {
		       
		        $this->width = $width;
		        $this->height = $height;
		       
		    }
		   

		    


		    if ( $this->zoomCrop ) {
		
		        $src_x = $src_y = 0;
		        $src_w = $width;
		        $src_h = $height;
		
		        $cmp_x = $width  / $this->width;
		        $cmp_y = $height / $this->height;
		
		        // calculate x or y coordinate and width or height of source
		
		        if ( $cmp_x > $cmp_y ) {
		
		            $src_w = round( ( $width / $cmp_x * $cmp_y ) );
		            $src_x = round( ( $width - ( $width / $cmp_x * $cmp_y ) ) / 2 );
		
		        } elseif ( $cmp_y > $cmp_x ) {
		
		            $src_h = round( ( $height / $cmp_y * $cmp_x ) );
		            $src_y = round( ( $height - ( $height / $cmp_y * $cmp_x ) ) / 2 );
		
		        }
		        
     		    $this->canvas = imagecreatetruecolor( $this->width, $this->height );
    		    imagealphablending( $this->canvas, false );
    		    // Create a new transparent color for image
    		    $color = imagecolorallocatealpha( $this->canvas, 0, 0, 0, 127 );
    		    // Completely fill the background of the new image with allocated color.
    		    imagefill( $this->canvas, 0, 0, $color );
    		    // Restore transparency blending
    		    imagesavealpha( $this->canvas, true );
    		    
		        imagecopyresampled( $this->canvas, $image, 0, 0, $src_x, $src_y, $this->width, $this->height, $src_w, $src_h );
		
		    } else {
		      
    		    // generate new w/h 
    		    if ( $width > $height ) {
    		       
    		        $this->height = $height * ( $this->width / $width );
    		       
    		    } elseif ( $height < $width ) {
    		       
    		        $this->width = $width * ( $this->height / $height );
    		       
    		    } elseif ( !$this->width && !$this->height ) {
    		       
    		        $this->width = $width;
    		        $this->height = $height;
    		       
    		    }

    		        
    		     //   var_dump($this->width);
    		      //  var_dump($this->height);
    		     //   var_dump($width);
    		     //   var_dump($height);
    		        
     		    $this->canvas = imagecreatetruecolor( $this->width, $this->height );
    		    imagealphablending( $this->canvas, false );
    		    // Create a new transparent color for image
    		    $color = imagecolorallocatealpha( $this->canvas, 0, 0, 0, 127 );
    		    // Completely fill the background of the new image with allocated color.
    		    imagefill( $this->canvas, 0, 0, $color );
    		    // Restore transparency blending
    		    imagesavealpha( $this->canvas, true );
		        // copy and resize part of an image with resampling
		        imagecopyresampled( $this->canvas, $image, 0, 0, 0, 0, $this->width, $this->height, $width, $height );
		
		    }
		    if ( $filters != "" ) {
		        // apply filters to image
		        $filterList = explode( "|", $filters );
		        foreach ( $filterList as $fl ) {

		            $filterSettings = explode( ",", $fl );
		            if ( isset( $this->imageFilters[ $filterSettings[ 0 ] ] ) ) {
		           
		                for ( $i = 0; $i < 4; $i ++ ) {
		                    if ( !isset( $filterSettings[ $i ] ) ) {
		                        $filterSettings[ $i ] = null;
		                    }
		                }
		               
		                switch ( $this->imageFilters[ $filterSettings[ 0 ] ][ 1 ] ) {
		               
		                    case 1:
		                   
		                        imagefilter( $this->canvas, $this->imageFilters[ $filterSettings[ 0 ] ][ 0 ], $filterSettings[ 1 ] );
		                        break;
		                   
		                    case 2:
		                   
		                        imagefilter( $this->canvas, $this->imageFilters[ $filterSettings[ 0 ] ][ 0 ], $filterSettings[ 1 ], $filterSettings[ 2 ] );
		                        break;
		                   
		                    case 3:
		                   
		                        imagefilter( $this->canvas, $this->imageFilters[ $filterSettings[ 0 ] ][ 0 ], $filterSettings[ 1 ], $filterSettings[ 2 ], $filterSettings[ 3 ] );
		                        break;
		                   
		                    default:
		                   
		                        imagefilter( $this->canvas, $this->imageFilters[ $filterSettings[ 0 ] ][ 0 ] );
		                        break;
		                       
		                }
		            }
		        }
		    }
		   
		    // output image to browser based on mime type
		  $returnImage = $this->show_image( $mime_type, $this->canvas,  $this->cache_dir );
		 
		  return $returnImage;
		} else {
		
		    if ( strlen( $src ) ) {
		        $this->displayError( "image " . $src . " not found" );
		    } else {
		        $this->displayError( "no source specified" );
		    }
		   
		}		
	} 
	
	function show_image( $mime_type, $image_resized, $cache_dir ) {

	  //  global $quality;
	
	    // check to see if we can write to the cache directory
	    $is_writable = 0;
	    $cache_file_name = $this->path . $cache_dir . '/' . $this->get_cache_file();
	
	    if ( touch( $cache_file_name ) ) {
	       
	        // give 666 permissions so that the developer
	        // can overwrite web server user
	        chmod( $cache_file_name, 0666 );
	        $is_writable = 1;
	       
	    } else {
	       
	        $cache_file_name = NULL;
	        header( 'Content-type: ' . $mime_type );
	       
	    }

	    $this->quality = floor( $this->quality * 0.09 );
	
	    //imagepng($image_resized, $cache_file_name, $this->quality);
	    imagejpeg( $image_resized, $cache_file_name, $this->quality );
	   
	    if ( $is_writable ) {
	     
	       return $this->show_cache_file( $cache_dir, $mime_type );
	    }
	
	    imagedestroy( $image_resized );
	   
	    $this->displayError( "error showing image" );
	
	}

	function open_image( $mime_type, $src ) {
	
	    if ( stristr( $mime_type, 'gif' ) ) {
	   
	        $image = imagecreatefromgif( $src );
	       
	    } elseif ( stristr( $mime_type, 'jpeg' ) ) {
	   
	        @ini_set( 'gd.jpeg_ignore_warning', 1 );
	        $image = imagecreatefromjpeg( $src );
	       
	    } elseif ( stristr( $mime_type, 'png' ) ) {
	   
	        $image = imagecreatefrompng( $src );
	       
	    }
	   
	    return $image;
	
	}
	
	/**
	* clean out old files from the cache
	* you can change the number of files to store and to delete per loop in the defines at the top of the code
	*/
	function cleanCache() {
	
	    $files = glob( "cache/*", GLOB_BRACE );
	   
	    $yesterday = time() - ( 24 * 60 * 60 );
	   
	    if ( count( $files ) > 0) {
	       
	        usort( $files, "filemtime_compare" );
	        $i = 0;
	       
	       // if (count($files) > CACHE_SIZE) {
	        if ( count( $files ) > $this->cacheSize ) {
	           
	            foreach ( $files as $file ) {
	               
	                $i ++;
	               
	               // if ($i >= CACHE_CLEAR) {
	                if ( $i >= $this->cacheClear ) {
	                    return;
	                }
	               
	                if ( filemtime( $file ) > $yesterday ) {
	                    return;
	                }
	               
	                unlink( $file );
	               
	            }
	           
	        }
	       
	    }
	
	}
	
	/**
	* compare the file time of two files
	*/
	function filemtime_compare( $a, $b ) {
	
	    return filemtime( $a ) - filemtime( $b );
	   
	}
	
	/**
	* determine the file mime type
	*/
	function mime_type( $file ) {
	
	    if ( stristr( PHP_OS, 'WIN' ) ) {
	        $os = 'WIN';
	    } else {
	        $os = PHP_OS;
	    }
	
	    $mime_type = '';
	
	    if ( function_exists( 'mime_content_type' ) ) {
	        $mime_type = mime_content_type( $file );
	    }
	   
	    // use PECL fileinfo to determine mime type
	    if ( !$this->valid_src_mime_type( $mime_type ) ) {
	        if ( function_exists( 'finfo_open' ) ) {
	            $finfo = finfo_open( FILEINFO_MIME );
	            $mime_type = finfo_file( $finfo, $file );
	            finfo_close( $finfo );
	        }
	    }
	
	    // try to determine mime type by using unix file command
	    // this should not be executed on windows
	    if ( !$this->valid_src_mime_type( $mime_type ) && $os != "WIN" ) {
	        if ( preg_match( "/FREEBSD|LINUX/", $os ) ) {
	            $mime_type = trim( @shell_exec( 'file -bi "' . $file . '"' ) );
	        }
	    }
	
	    // use file's extension to determine mime type
	    if ( !$this->valid_src_mime_type( $mime_type ) ) {
	
	        // set defaults
	        $mime_type = 'image/jpeg';
	        // file details
	        $fileDetails = pathinfo( $file );
	        $ext = strtolower( $fileDetails[ "extension" ] );
	        // mime types
	        $types = array(
	             'jpg'  => 'image/jpeg',
	             'jpeg' => 'image/jpeg',
	             'png'  => 'image/png',
	             'gif'  => 'image/gif'
	         );
	       
	        if ( strlen( $ext ) && strlen( $types[ $ext ] ) ) {
	            $mime_type = $types[ $ext ];
	        }
	       
	    }
	   
	    return $mime_type;
	
	}
	
	function valid_src_mime_type( $mime_type ) {
	
	    if ( preg_match( "/jpg|jpeg|gif|png/i", $mime_type ) ) {
	        return true;
	    }
	   
	    return false;
	
	}
	
	function check_cache( $cache_dir, $mime_type ) {
	
	    // make sure cache dir exists
	    if ( !file_exists( $this->path . $cache_dir ) ) {
	        // give 777 permissions so that developer can overwrite
	        // files created by web server user
	        mkdir( $this->path . $cache_dir );
	        chmod( $this->path . $cache_dir, 0777 );
	    }
	
	     return $this->show_cache_file( $cache_dir, $mime_type );
	
	}
	
	function show_cache_file( $cache_dir ) {
	
	    $cache_file = $cache_dir . '/' . $this->get_cache_file();
	
	    if ( file_exists( $this->path . $cache_file ) ) {
	       
	        $gmdate_mod = gmdate( "D, d M Y H:i:s", filemtime( $this->path . $cache_file ) );
	       
	        if ( !strstr( $gmdate_mod, "GMT" ) ) {
	            $gmdate_mod .= " GMT";
	        }
	       
	        if ( isset( $_SERVER[ "HTTP_IF_MODIFIED_SINCE" ] ) ) {
	       
	            // check for updates
	            $if_modified_since = preg_replace( "/;.*$/", "", $_SERVER[ "HTTP_IF_MODIFIED_SINCE" ] );
	           
	            if ( $if_modified_since == $gmdate_mod ) {
	               // header("HTTP/1.1 304 Not Modified");
	               // exit;
	               return FALSE;
	            }
	
	        }
	       
	        $fileSize = filesize( $this->path .  $cache_file );
	            
	     	return $cache_file;

	    }	   
	}
		
	private function get_cache_file() {
	
	    if( !$cache_file ) {
			$imgName = substr( $this->img , 0 , ( strpos( $this->img , '.' ) ) );
			//echo $imgName;
	        $cachename = $imgName . $this->version . $this->lastModified . $this->height . "x" . $this->width;
	        $cache_file = md5( $cachename ) . '.jpg';
	    }
	   
	    return $cache_file;
	
	}	
	
	private function valid_extension( $ext ) {

	    if ( preg_match( "/jpg|jpeg|png|gif/i", $ext ) ) {
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	   
	}
		
	private function displayError( $errorString = '' ) {

	    die( $errorStrings );
	   
	}
	
}

?>