<?php
/**
 *  Parent controller to process requests
 *   and have action for child controller 
 * 
 */

use RequestController as Request;

class MyController
{
	/**
	 * to hold db connection object
	 * @var object
	 */
	protected $conn;

	/**
	 * to hold controller name
	 * @var string
	 */
	protected $controller;

	/**
	 * to hold ation id i.e resource id
	 * @var int
	 */
	protected $resource_id;

	/**
	 * to hold request verb in lower case
	 * i.e get, post, put, patch & delete
	 * 
	 * @var string
	 */
	protected $request_verb;

	/**
	 * construct initialize db connection object
	 */
	public function __construct()
	{
		$db = new DatabaseConfig;
		$this->conn = $db->connect();

	}

	/**
	 * function to check and process all request
	 * verbs - GET, POST, PUT, PATCH and DELETE
	 * 
	 * @param  Request $request      object of Request
	 * @return array                 response
	 */
	public function processRequest(Request $request):array
	{
		// request verb
		$this->request_verb = strtolower($request->verb);

		$this->controller = $request->url_elements[4];

		$this->data = $request->parameters;

		// action id from request url
		if(isset($request->url_elements[5])){

			// for POST ID not needed
			if($this->request_verb=='post'){
				return $response = array('message' => 'ERROR: Bad Request','status' => '0');
			}
			
			$this->resource_id = (int)$request->url_elements[5];

			// invalid resource id
			if(($this->resource_id == 0) or empty($this->resource_id)){

				$response = array('message' => 'ERROR: Bad Request','status' => '0');

			} else{

				// create resource id variable and pass action id
				$resource_id_str = substr($this->controller, 0, -1).'_id';
				$this->$resource_id_str = $this->resource_id;

				// get resource result set row count 
				$num = $this->getResultSetRowCount();
		  	
		  		if($num > 0){

		  			 /*
		  			  * call action acording to GET, PUT,
		  			  *	 PATCH & DELETE verb
		  			  */
		  			$action = $this->request_verb.'Action';

		  			$response = $this->$action();
		  			
		  		} else {

		  			$response = array('message' => 'ERROR: resource id not found','status' => '0');
		  		}

			}			
		} else {

			// check for POST verb
			if($this->request_verb == 'post'){

				$response = $this->postAction();

				return $response;	
			}

			// check if GET request is for list resource
			if($this->request_verb == 'get'){

				$response = $this->getAllAction();

				return $response;				
			}

			// response for bulk action
			$response = array('message' => 'Bulk action curently not available!','status' => '0');
		}

		return $response;
	}


}
