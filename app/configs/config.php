<?php
/**
 * /configs/config.php
 * application level configuration for each deployment:
 * This config - holds standard connection information, and base decorator
 */

	$_config = array(
        "username"    => "user_******",
        "password"    => "********",
        "server"      => "localhost",
        "database"    => "database_*******",
        "default"     => "/coupon/index",
        "decorators"  => array("app"),
        // "no_log"   => array( "" ),
        "company"     => "Company Name",
        "salt"        => "SALT_GOES_HERE",
        "twitter"     => array ( 
            // "username" => "",
            // "password" => ""
        ),
            "image"   => array(
            "imgDir"  => "/app/images/"
        )

    );
				

?>