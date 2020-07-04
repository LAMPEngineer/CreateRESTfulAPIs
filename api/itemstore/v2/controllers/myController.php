<?php
/**
 *  Parent controller to process requests
 *   and have action for child controller 
 * 
 */

class MyController
{

	/**
	 * to hold request data
	 * @var array
	 */
	protected $data= array();

	/**
	 * to hold view format
	 * @var string 
	 */
	protected $format;

	/**
	 * resourse ID
	 * @var int
	 */
	protected $id;

	/**
	 * to hold model object
	 * @var object
	 */
	protected $model;


	// setter methods 
	public function setData($data){ $this->data = $data; }
	public function setFormat($format){ $this->format = $format; }
	public function setId($id){ $this->id = $id; }


	/**
	 * to get row count for a resource 
	 * @return int     row count
	 */
	public function getResultSetRowCount(): int
	{
		$num = 0;

		// call read action on model object
		$this->model->setId($this->id);

		// get row count
		 $num = $this->model->getResultSetRowCountById();

		return $num;
	}


	/**
	 * Action for GET verb to list resource 
	 * 
	 * @return array   response
	 */
	function getAllAction(): array
	{
	  // call read action on model object			
	  $data = $this->model->getAll();

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
		$data = $this->model->getDetailsById();

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

		$table_fields = $this->model->getTableFields();

		foreach ($this->data as $key => $value) {

		  // get values
		  $setter_value = (array_key_exists($key, $table_fields)) ? $key : null;

		  if (!empty($setter_value)) {

		  	 // validation
		    $setter_value = $this->validateParameter($key, $this->data[$key], false);
		  	
		    // values to model
		    $this->setSetterMethodWithValue($key, $setter_value);
		  }

		}

		// call insert action on model object		
		$result = $this->model->insert();

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

		$data_old = $this->model->getDetailsById();

		foreach ($data_old as $key => $value) {

  		  // get values
		  $setter_value = (array_key_exists($key, $this->data)) ? $this->data[$key] : null;
		  if (!empty($setter_value)) {

		  	// validation
		    $setter_value = $this->validateParameter($key, $setter_value, false);
		  	
		    // values to model
		    $this->setSetterMethodWithValue($key, $setter_value);
		  } 

		}

		$this->model->setId($this->id);


		//call update action on model object
		$result = $this->model->update('put');

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

		$table_fields = $this->model->getTableFields();

	  	//foreach ($this->data as $key => $value) {
	  	foreach ($table_fields as $key => $value) {

		  if (!empty($this->data[$key])) {

		  	  // get values	
		  	  $setter_value = $this->data[$key];

		  	  // validation
		  	  $setter_value = $this->validateParameter($key, $setter_value, false);
		  	  // values to model
		  	  $this->setSetterMethodWithValue($key, $setter_value);

		  } 

		}
 			
		// call update action on model object		
		$result = $this->model->update('patch');

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
		// call delete action on model object	
		$this->model->setId($this->id);

		$result = $this->model->delete();

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
    protected function setSetterMethodWithValue($setter_key, $setter_value): bool
    {
    	$table_fields = $this->model->getTableFields();

    	$setter_method = 'set'.ucfirst($table_fields[$setter_key]['method']);

		$this->model->$setter_method($setter_value);

		return true;

    }


	/**
	 *  Validate parameter for string, int, boolean etc.
	 *  and also htmlspecialchars & strip_tags
	 *  
	 * @param  [string]  $fieldName 
	 * @param  [string]  $value     
	 * @param  [boolean] $required  
	 * 
	 * @return [string]  $value          
	 */
	public function validateParameter($fieldName, $value, $required = true)
	{
		$table_fields = $this->model->getTableFields();

		if($required == true && empty($value) == true){
			return array('message' => $fieldName.' parameter is required.','status' => '0');
		}

		switch ($table_fields[$fieldName]['type']) {
			
			case 'BOOLEAN':
				if (!is_bool($value)) {

					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'INT':
				if (!is_int($value)) {

					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'INTEGER':
				if (!is_numeric($value)) {
					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'STRING':
				if (!is_string($value)) {
					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}				
				break;
			
			default:
				$this->throwError('0', 'Data type is not valid for '. $fieldName);
				break;
		}

		return htmlspecialchars(strip_tags($value));
		
	}


	/**
	 * To throw error if occure. It uses view
	 *  format i.e. json to send message
	 * 
	 * @param  [string] $code    
	 * @param  [string] $message 
	 * 
	 * @return exit         
	 */
	public function throwError($code, $message)
	{
		$content = array('message' => $message,'status' => $code);

		// view format
		$view_name = ucfirst($this->format) . 'View';

		if(class_exists($view_name)){

			$view = new $view_name();
			$view->render($content);		
		}

		exit;		
	}

}
