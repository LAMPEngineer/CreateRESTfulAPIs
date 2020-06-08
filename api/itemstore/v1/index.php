<?php
/**
 *  Request handler that route request to right place.
 *  It calls action of the controller and return back
 *  response in right format as requested.
 *  
 */

// check PATH_INFO
if(!isset($_SERVER['PATH_INFO'])){
	// redirect to root
	header("Location: ../../../");
}

//autoload 
include __DIR__ . '\autoload.php';

// request object
$request = new RequestController();

/*print_r($request->url_elements);
die;*/
// route the request to the right place
$controller_name = ucfirst($request->url_elements[1]) . 'Controller';


if(class_exists($controller_name)){
	
	$controller = new $controller_name();
	
	// call action
	$result = $controller->processRequest($request);

	// view format
	$view_name = ucfirst($request->format) . 'View';

	if(class_exists($view_name)){

		$view = new $view_name();
		$view->render($result);		
	}

}


