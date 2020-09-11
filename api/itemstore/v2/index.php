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

$service = '';
$service_action ='';


// check for auth request
if($request->url_elements[1]=='auth'){
	$action_name = $service = 'Auth';
	
	if(!empty($request->url_elements[2])){
		// second element is the action 
		$service_action = ucfirst($request->url_elements[2]);
	} else MyTrait::throwError('0', 'ERROR: Bad Request');
	
	
}else{
	// check headers for authorization token
	$all_headers = getallheaders(); 
	if(!empty($all_headers['Authorization'])) {

		//MyTrait::read token...
		$response = (object)MyTrait::readTokenFromHeadersOrPostData();
		if($response->status!='1')MyTrait::throwError('0', 'ERROR: Not authorize');
		
	 	} else MyTrait::throwError('0', 'ERROR: authorization token missing');


	/**
	 * Here, authentication done.
	 *  
	 * By default, first element is action i.e the controller 
	 * @var [type]
	 */
	$service = ucfirst($request->url_elements[1]);;
	//$action_name = ucfirst($request->url_elements[1]);
	
	switch ($service) {
		case 'Search':

			if(!empty($request->url_elements[2])){
				// second element is the action 
				$service_action = ucfirst($request->url_elements[2]);

				//create search object
				$service_action_obj  = MyTrait::buildObject($service_action);
				$service_obj = Container::get($service.'Controller', $service_action_obj);

				$service_action = '';
				
			} else MyTrait::throwError('0', 'ERROR: Bad Request');	

		break;
		
		default:
			$action_name = $service;
			$service_action ='';
			$service = '';
			break;
	}

}

// create object
$service_obj = ($service != 'Search') ? MyTrait::buildObject($action_name) : $service_obj;

$process_action = 'process'. $service . 'Request';

// route the request to the right place
$result = $request->$process_action($service_obj, $service_action);

$request->sendResponse($result);