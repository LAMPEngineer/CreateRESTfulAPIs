<?php
/**
 *  Request handler that route request to right place.
 *  It calls action of the controller and return back
 *  response in right format as requested.
 *  
 */

use ContainerController as Container;


// check PATH_INFO
if(!isset($_SERVER['PATH_INFO'])){
	// redirect to root
	header("Location: ../../../");
}

//autoload 
include __DIR__ . '\autoload.php';


// request object
$request = Container::get('RequestController');

$auth = '';
$action ='';
// check for auth request
if($request->url_elements[1]=='auth'){

	$auth = 'Auth';

	// for auth controller is the users
	$action_name = 'Users';

	if(!empty($request->url_elements[2])){

		// action is the second element
		$action = ucfirst($request->url_elements[2]);

	} else {

		$result = array('message' => 'ERROR: Bad Request','status' => '0');
		$request->sendResponse($result);
	}

	
}else{

	// for rest, controller is the first element
	$action_name = ucfirst($request->url_elements[1]);
}

$controller = $request->buildControllerObject($action_name); 

$process_action = 'process'. $auth . $action . 'Request';

// route the request to the right place
$result = $request->$process_action($controller);

$request->sendResponse($result);