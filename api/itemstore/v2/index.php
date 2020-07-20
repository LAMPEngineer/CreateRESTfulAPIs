<?php
/**
 *  Request handler that route request to right place.
 *  It calls action of the controller and return back
 *  response in right format as requested.
 *  
 */

//autoload 
include __DIR__ . '/autoload.php';
include  '../vendor/autoload.php';

use MyTraitController as MyTrait;
use ContainerController as Container;


// check PATH_INFO
if(!isset($_SERVER['PATH_INFO'])){
	// redirect to root
	header("Location: ../../../");
}


// request object
$request = Container::get('RequestController');

$auth = '';
$auth_action ='';

// check for auth request
if($request->url_elements[1]=='auth'){
	$action_name = $auth = 'Auth';
	
	if(!empty($request->url_elements[2])){
		// second element is the action 
		$auth_action = ucfirst($request->url_elements[2]);
	} else {
		$result = array('message' => 'ERROR: Bad Request','status' => '0');
		$request->sendResponse($result);
	}
	
}else{

	// check headers for authorization token
	$all_headers = getallheaders(); 

/*	var_dump($all_headers);
	die;*/
	if(empty($all_headers['Authorization'])) {
		$result = array('message' => 'ERROR: authorization token missing','status' => '0');
		$request->sendResponse($result);
	}


	// without auth, first element is action i.e the controller 
	$action_name = ucfirst($request->url_elements[1]);
}

// create object
$controller = MyTrait::buildObject($action_name);

$process_action = 'process'. $auth . 'Request';

// route the request to the right place
$result = $request->$process_action($controller, $auth_action);

$request->sendResponse($result);