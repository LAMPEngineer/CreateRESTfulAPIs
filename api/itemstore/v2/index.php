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

use ContainerController as Container;


// check PATH_INFO
if(!isset($_SERVER['PATH_INFO'])){
	// redirect to root
	header("Location: ../../../");
}


// request object
$request = Container::get('RequestController');

$request->processRequest();