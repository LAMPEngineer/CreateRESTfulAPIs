<?php
	
/*
 *  Items controller to have actions for items
 */

class ItemsController extends MyController implements ItemsInterface
{
	/**
	 * to hold item model object
	 * @var object
	 */
	protected $item_model;

	/**
	 * to hold request data
	 * @var array
	 */
	protected $data= array();

	/**
	 * resourse ID
	 * @var int
	 */
	protected $item_id;



	/**
	 * construct initialize db connection object
	 */
	public function __construct()
	{
		parent::__construct();

		//Get item model object
		$this->item_model = new ItemModel($this->conn);
	}




	/**
	 * to get row count for a resource 
	 * @return int     row count
	 */
	protected function getResultSetRowCount(): int
	{
		$num = 0;

		// call read action on item model object
		$this->item_model->setId($this->item_id);

		// get row count
		 $num = $this->item_model->getResultSetRowCountById();

		return $num;
	}




	/**
	 * Action for GET verb to list resource 
	 * 
	 * @return array   response
	 */
	function getAllAction(): array
	{
	  // call read action on item model object			
	  $data = $this->item_model->getAllItems();

	  $response = array('message' => 'list resource ','status' => '1', 'data' => $data);

	  return $response;	

	}




	/**
	 * Action for GET verb to read an individual resource 
	 * 
	 * @return array   response
	 */
	function getAction(): array
	{
		$data = $this->item_model->getItemDetailsById();

		$response = array('message' => 'info of individual resource ','status' => '1', 'data' => $data);

		return $response;
	}




	/**
	 * Action for POST verb to create an individual resource 
	 * 
	 * @return array   response
	 */
	function postAction(): array
	{
		// empty data, return status as 0
		if(empty($this->data)){			
			$response =	array('message' => 'invalid resource data','status' => '0');
			return $response;			
		}

		$item_table_fields = $this->item_model->getItemTableFields();

		foreach ($this->data as $key => $value) {

		  // get values
		  $setter_value = (array_key_exists($key, $item_table_fields)) ? $key : null;

		  if (!empty($setter_value)) {

		  	 // validation
		    $setter_value = $this->validateParameter($key, $this->data[$key], false);
		  	
		    // values to model
		    $this->setItemSetterMethodWithValue($key, $setter_value);
		  }

		}

		// call insert action on item model object		
		$result = $this->item_model->insert();

		$response = ($result) ? array('message' => 'resource submitted','status' => '1') : array('message' => 'resource not submitted','status' => '0');

		return $response;

	}



	/**
	 * Action for PUT verb to update an individual resource 
	 * 
	 * @return array   response
	 */
	function putAction(): array
	{

		$data_old = $this->item_model->getItemDetailsById();

		foreach ($data_old as $key => $value) {

  		  // get values
		  $setter_value = (array_key_exists($key, $this->data)) ? $this->data[$key] : null;
		  if (!empty($setter_value)) {

		  	// validation
		    $setter_value = $this->validateParameter($key, $setter_value, false);
		  	
		    // values to model
		    $this->setItemSetterMethodWithValue($key, $setter_value);
		  } 

		}

		$this->item_model->setId($this->item_id);


		/**
		 * call update action on item model object
		 * @param string $request_verb [put]
		 */		 	
		$result = $this->item_model->update($this->request_verb);

		$response = ($result) ? array('message' => 'resource updated','status' => '1') : array('message' => 'resource not updated','status' => '0');

		return $response;

	}



	/**
	 * Action for PATCH verb to update an individual resource 
	 * 
	 * @return array   response
	 */
	function patchAction(): array
	{

		$item_table_fields = $this->item_model->getItemTableFields();

	  	//foreach ($this->data as $key => $value) {
	  	foreach ($item_table_fields as $key => $value) {

		  if (!empty($this->data[$key])) {

		  	  // get values	
		  	  $setter_value = $this->data[$key];

		  	  // validation
		  	  $setter_value = $this->validateParameter($key, $setter_value, false);
		  	  // values to model
		  	  $this->setItemSetterMethodWithValue($key, $setter_value);

		  } 

		}
 			
		// call update action on item model object		
		$result = $this->item_model->update($this->request_verb);

		$response = ($result) ? array('message' => 'PATCHES - resource updated','status' => '1') : array('message' => 'PATCHES - resource not updated','status' => '0');

		return $response;
	}




	/**
	 * Action for DELETE verb to delete an individual resource 
	 * 
	 * @return array     response
	 */
	function deleteAction(): array
	{
		// call delete action on item model object	
		$this->item_model->setId($this->item_id);

		$result = $this->item_model->delete();

		$response = ($result) ? array('message' => 'resource deleted','status' => '1') : array('message' => 'resource not deleted','status' => '0');

		return $response;
	}


	/**
	 *  Dynamically create setter and pass value to it
	 *  to set into model  
	 *  
	 * @param string $setter_key   key to creaye setter
	 * @param string $setter_value value to pass into setter 
	 *
	 * @return boolean
	 */
    protected function setItemSetterMethodWithValue($setter_key, $setter_value): bool
    {
    	$item_table_fields = $this->item_model->getItemTableFields();

    	$setter_method = 'set'.ucfirst($item_table_fields[$setter_key]['method']);

		$this->item_model->$setter_method($setter_value);

		return true;

    }

	/**
	 * function to check and process all request
	 * verbs - GET, POST, PUT, PATCH and DELETE
	 * 
	 * @param  Request $request      object of Request
	 * @return array                 response
	 */
	function processRequest(RequestInterface $request):array
	{
		// requested verb
		$this->request_verb = strtolower($request->verb);

		// requested controller
		$this->controller = $request->url_elements[1];

		// requested data
		$this->data = $request->parameters;

		// requested format
		$this->format = $request->format;

		// action id from request url
		if(isset($request->url_elements[2])){

			// for POST ID not needed
			if($this->request_verb=='post'){
				return $response = array('message' => 'ERROR: Bad Request','status' => '0');
			}
			
			$this->resource_id = (int)$request->url_elements[2];

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