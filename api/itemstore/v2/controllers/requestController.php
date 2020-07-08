<?php

use ContainerController as Container;

/*
 *  Request handler to handle the RESTful requests
 */
class RequestController implements RequestInterface
{
	/**
	 * request url elements
	 * @var string
	 */
	public $url_elements;

	/**
	 * HTTP verbs viz. GET, POST, PUT, DELETE
	 * @var string
	 */
	public $verb;

	/**
	 * request parameters
	 * @var array
	 */
	public $parameters;

	/**
	 * constructor to get incoming request
	 */
	public function __construct()
	{
		$this->verb = $_SERVER['REQUEST_METHOD'];
		$this->url_elements = explode('/', $_SERVER['PATH_INFO']);

		$this->parseIncomingParams();

		//initialise json as default format
		$this->format = 'json';
		if(isset($this->parameters['format'])){
			$this->format = $this->parameters['format'];
		}
	}

	/**
	 * Parse incoming parameters	
	 */
	public function parseIncomingParams()
	{
		$parameters = array();
		
		// first pull the GET vars
		if(isset($_SERVER['QUERY_STRING'])){
			parse_str($_SERVER['QUERY_STRING'], $parameters);
		}

		// pull POST/PUT bodies
		$body = file_get_contents("php://input");
		$content_type = false;
		if(isset($_SERVER['CONTENT_TYPE'])){
			$content_type = $_SERVER['CONTENT_TYPE'];
		}
		switch ($content_type) {
			case "application/json":
				$body_params = json_decode($body);
				if($body_params){
					foreach ($body_params as $param_name => $param_value) {
						$parameters[$param_name] = $param_value;
					}
				}
				$this->format = "json";

				break;
			
			case "application/x-www-form-urlencoded":
				parse_str($body, $postvars);
				foreach ($postvars as $field => $value) {
					$parameters[$field] = $value;
				}
				$this->format = "html";

				break;

			default:
				# we could parse other supported formats here
				break;
		}

		$this->parameters = $parameters;
	}



	/**
	 * function to check and process all request
	 * and passon to the controller
	 * 
	 * @param  ControllerInterface $controller object of Controller
	 * @return array response
	 */
	public function processRequest(ControllerInterface $controller):array
	{

		// pass requested data to the controller 
		$controller->setData($this->parameters);

		// pass requested format to the controller
		$controller->setFormat($this->format);

		// action id from request url
		if(isset($this->url_elements[2])){

			// for POST ID not needed
			if($this->verb=='POST'){
				return $response = array('message' => 'ERROR: Bad Request','status' => '0');
			}
			
			$this->resource_id = (int)$this->url_elements[2];

			// invalid resource id
			if(($this->resource_id == 0) or empty($this->resource_id)){

				$response = array('message' => 'ERROR: Bad Request','status' => '0');

			} else{

				// pass resource id to the controller				
				$controller->setId($this->resource_id);

				// get resource result set row count 
				$num = $controller->getResultSetRowCount();
		  	
		  		if($num > 0){

		  			 /*
		  			  * call action acording to GET, PUT,
		  			  *	 PATCH & DELETE verb
		  			  */
		  			$action = $this->verb.'Action';

		  			$response = $controller->$action();
		  			
		  		} else {

		  			$response = array('message' => 'ERROR: resource id not found','status' => '0');
		  		}

			}			
		} else {

			// check for POST verb
			if($this->verb == 'POST'){

				$response = $controller->postAction();

				return $response;	
			}

			// check if GET request is for list resource
			if($this->verb == 'GET'){

				$response = $controller->getAllAction();

				return $response;				
			}

			// response for bulk action
			$response = array('message' => 'Bulk action curently not available!','status' => '0');
		}

	
		return $response;
	}


	/**
	 * function to process auth login request
	 * 
	 * @param  ControllerInterface $controller 
	 * @return array                          
	 */
	public function processAuthLoginRequest(ControllerInterface $controller): array
	{
		// pass requested data to the controller 
		$controller->setData($this->parameters);

		// pass requested format to the controller
		$controller->setFormat($this->format);

		$response = array('message' => 'Auth - login','status' => '1');	

		
		return $response;

	}



	/**
	 * function to process auth token requests
	 * 
	 * @param  ControllerInterface $controller 
	 * @return array                          
	 */
	public function processAuthTokenRequest(ControllerInterface $controller): array
	{
		// pass requested data to the controller 
		$controller->setData($this->parameters);

		// pass requested format to the controller
		$controller->setFormat($this->format);

		$response = array('message' => 'Auth - token','status' => '1');	

		
		return $response;

	}




	/**
	 * function to send response in defined format
	 * by default is json format
	 * 
	 * @param  array $result 
	 * @return exit
	 */
	public function sendResponse(array $result)
	{

		// view format
		$view_name = ucfirst($this->format) . 'View';

		if(class_exists($view_name)){

			$view = Container::get($view_name);

			$view->render($result);		
		}

		exit;
	}



	/**
	 * function to build controller object as needed
	 * in processing of requests
	 * 
	 * @param  string $action_name 
	 * @return object             
	 */
	public function buildControllerObject(string $action_name): object
	{

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

			return $controller;
		}

	}



}