<?php
/**
 * Router controls the initial application bootup.
 *
 * @package base
 * @author Charles Abbott
 */

/**
 * Initial routing includes the base config and application config
 * - require the base functions
 * - require the base config (tells us the default app + additional parameters)
 * - require the application config, first by $_GET['application'] if available, then by the default app setup in the base config
 * - set the session name either by the config's "session" setting, or by the $_GET['application'] variable
 * - start the session and save the application name inside the session
 * - if there is a config for after-session setup then require it (used for setting session variables directly after the session starts)
 */
	require_once('functions.php');
	require_once(dirname(__FILE__)."/../configs/config.php");

	$_GET["application"] = str_replace(".","_",$_GET["application"]);
	$_get_file = dirname(__FILE__)."/../../".$_GET["application"]."/configs/config.php";
	if(isset($_GET["application"]) && file_exists($_get_file)){
		require_once($_get_file);
		(isset($_config["session"])) ? session_name($_config["session"]) : session_name($_GET["application"]);
	}else{
		require_once(dirname(__FILE__)."/../../".$_base_config["app"]."/configs/config.php");
		(isset($_base_config["session"])) ? session_name($_base_config["session"]) : session_name($_base_config["app"]);
	}
	set_time_limit(60);
	session_start();
	setmvc("app", session_name());
	$_application_functions = dirname(__FILE__)."/../../".mvc('app')."/controllers/functions.php";
	if(file_exists($_application_functions)) require_once($_application_functions);

	$_session_config = dirname(__FILE__)."/../../".mvc('app')."/configs/session_config.php";
	if(file_exists($_session_config) && !mvc('session_config')){
		require_once($_session_config);
		mvc('session_config',true);
	}
/**
 * At this point the application config is included and the session name is set
 * mvc('app') also has the current application saved, the application specific functions
 * have been included and any session_config has executed
 *
 * now we will prepare a few more session variables and then get to the request
 */
	setmvc('runtime_loaded_classes');
	setmvc('schema_connect_name',$_config["schema"]);

	//this is used by a few base classes as a default
	$session = mvc("config");
	$config = (!empty($session)) ? mvc('config') : "config";
	setmvc('config',$config);

/**
 * Prepare the application for static and runtime decorator classes
 */
	$session = mvc('runtime_decorator_class_definitions');
	if(!empty($session)) eval(mvc('runtime_decorator_class_definitions'));
	$session = mvc('decorators');
	$decorators = (!empty($session)) ? mvc('decorators') : $_config["decorators"];
	setmvc('decorators', $decorators);
	if ( !is_array( mvc( 'runtime_decorators' ) ) ) setmvc( 'runtime_decorators' , array() );

/**
 * Now:
 * - if a Request object exists in $_SESSION we will wake it up and use it to execute the requests
 * - if no Request object exists, lets create a new one to use for this session
 *
 * The Request object automatically saves itself to the $_SESSION upon completion of the script (inside the __destruct magic method)
 * so after the first request of this session we should have that same Request object for subsequent communications
 */

	require_once( dirname(__FILE__) . '/Request.php' );
	$mvc_request = mvc( 'request' );
	
	if ( !is_object( $mvc_request ) ) {
		$mvc_request = new Request( $_config );
	}
	$mvc_request->register_post_request();
	$mvc_request->register_get_request();

	$mvc_request->execute_requests();

?>