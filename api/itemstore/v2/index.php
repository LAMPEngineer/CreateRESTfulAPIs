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

// route the request to the right place
$action_name = ucfirst($request->url_elements[1]) ;

$controller_name = $action_name . 'Controller';

if(class_exists($controller_name)){
	
	// PDO db object
	$db = Container::get('DatabaseConfig');
	$conn = $db->connect();

	// model object
	$model_name = $action_name . 'Model';

	$model = Container::get($model_name, $conn);

	//$controller object 
	$controller = Container::get($controller_name, $model);
	
	// call action
	$result = $request->processRequest($controller);

	// view format
	$view_name = ucfirst($request->format) . 'View';

	if(class_exists($view_name)){

		$view = Container::get($view_name);
		$view->render($result);		
	}

}

