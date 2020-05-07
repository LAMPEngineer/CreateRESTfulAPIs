<?php
	
/*
 *  Items controller to have actions for items
 */

use RequestController as Request;


class ItemsController
{
	/**
	 * to hold db connection object
	 * @var object
	 */
	private $conn;

	/**
	 * to hold item model object
	 * @var object
	 */
	private $item_model;

	/**
	 * to hold request data
	 * @var array
	 */
	private $data= array();

	/**
	 * to hold PDO result set object
	 * @var object
	 */
	private $result;

	/**
	 * resourse ID
	 * @var int
	 */
	private $item_id;

	/**
	 * construct initialize db connection object
	 */
	public function __construct()
	{
		$db = new DatabaseConfig;
		$this->conn = $db->connect();

		//Get item model object
		$this->item_model = new ItemModel($this->conn);
	}

	/**
	 * function to controll and check conditions for all requested
	 * verbs - GET, POST, PUT, PATCH and DELETE
	 * 
	 * @param  Request $request      object of Request
	 * @param  string  $request_verb requested verb
	 * @return array                 response
	 */
	public function controllerAction(Request $request, string $request_verb):array
	{

		$this->data = $request->parameters;

		// item id from request url
		if(isset($request->url_elements[5])){

			// for POST ID not needed
			if($request_verb=='post'){
				return $response = array('message' => 'ERROR: Bad Request','status' => '0');
			}


			$this->item_id = (int)$request->url_elements[5];

			// invalid item id
			if(($this->item_id == 0) or empty($this->item_id)){

				$response = array('message' => 'ERROR: Bad Request','status' => '0');

			} else{

				// call read action on item model object		
				$this->result = $this->item_model->read($this->item_id);

				// get row count
		  		$num = $this->result->rowCount();

		  		if($num > 0){

		  			switch ($request_verb) {

		  				case 'get':
	  						$response = $this->getAction();
	  					break;

	  					case 'put':
	  						$response = $this->putAction();
	  					break;

	  					case 'patch':
	  						$response = $this->patchAction();						
						break;

		  				case 'delete':
		  					$response = $this->deleteAction();
		  					break;
		  				
		  				default:
		  					# code...
		  					break;
		  			}

		  		} else {

		  			$response = array('message' => 'ERROR: resource id not found','status' => '0');
		  		}

			}			
		} else {

			// check for POST verb
			if($request_verb == 'post'){

				$response = $this->postAction();

				return $response;	
			}

			// check if GET request is for list resource
			if($request_verb == 'get'){

				$response = $this->getAllAction();

				return $response;				
			}

			// response for bulk action
			$response = array('message' => 'Bulk action curently not available!','status' => '0');
		}

		return $response;
	}

	/**
	 * Action for GET verb to list resource 
	 * 
	 * @return array            response
	 */
	private function getAllAction(): array
	{
		// call read action on item model object		
		$result = $this->item_model->read();

		// fetch all PDO result set
  		$data = $result->fetchAll(PDO::FETCH_ASSOC);

		$response = array('message' => 'list resource ','status' => '1', 'data' => $data);

		return $response;

	}


	/**
	 * Action for GET verb to read an individual resource 
	 * 
	 * @return array            response
	 */
	private function getAction(): array
	{
		$data = $this->result->fetch(PDO::FETCH_ASSOC);

		$response = array('message' => 'info of individual resource ','status' => '1', 'data' => $data);

		return $response;
	}

	/**
	 * Action for POST verb to create an individual resource 
	 * 
	 * @return array            response
	 */
	private function postAction(): array
	{
		// empty data, return status as 0
		if(empty($this->data)){			
			$response =	array('message' => 'invalid resource data','status' => '0');
			return $response;			
		}

		$data_clean['name'] = htmlspecialchars(strip_tags($this->data['name']));
		$data_clean['description'] = htmlspecialchars(strip_tags($this->data['description']));

		// call create action on item model object		
		$result = $this->item_model->create($data_clean);

		$response = ($result) ? array('message' => 'resource submitted','status' => '1') : array('message' => 'resource not submitted','status' => '0');

		return $response;

	}

	/**
	 * Action for PUT verb to update an individual resource 
	 * 
	 * @return array            response
	 */
	private function putAction(): array
	{
		// fetch PDO result set
		$data_old = $this->result->fetchAll(PDO::FETCH_ASSOC);			  			

		foreach ($data_old[0] as $key => $value) {

			$data_clean[$key] = (array_key_exists($key, $this->data)) ? htmlspecialchars(strip_tags($this->data[$key])) : null;
		}

		$data_clean['id'] = $this->item_id;


		// call update action on item model object		
		$result = $this->item_model->update($data_clean, $this->item_id);

		$response = ($result) ? array('message' => 'resource updated','status' => '1') : array('message' => 'resource not updated','status' => '0');

		return $response;

	}

	/**
	 * Action for PATCH verb to update an individual resource 
	 * 
	 * @return array            response
	 */
	private function patchAction(): array
	{
	  	foreach ($this->data as $key => $value) {

			$data_clean[$key] = (!empty($this->data[$key])) ? htmlspecialchars(strip_tags($this->data[$key])) : null;

		}

		$data_clean['id'] = $this->item_id;

		// call update action on item model object		
		$result = $this->item_model->update($data_clean, $this->item_id);

		$response = ($result) ? array('message' => 'PATCHES - resource updated','status' => '1') : array('message' => 'PATCHES - resource not updated','status' => '0');

		return $response;
	}

	/**
	 * Action for DELETE verb to delete an individual resource 
	 * 
	 * @return array            response
	 */
	private function deleteAction(): array
	{
		// call delete action on item model object		
		$result = $this->item_model->delete($this->item_id);

		$response = ($result) ? array('message' => 'resource deleted','status' => '1') : array('message' => 'resource not deleted','status' => '0');

		return $response;
	}


}