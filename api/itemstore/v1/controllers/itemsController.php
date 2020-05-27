<?php
	
/*
 *  Items controller to have actions for items
 */

class ItemsController extends MyController
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
	 * to hold PDO result set object
	 * @var object
	 */
	protected $result;

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
	 * @param  int $item_id - resource id
	 * @return int     row count
	 */
	protected function getResultSetRowCount(): int
	{
		$num = 0;

		// call read action on item model object
		$this->item_model->id = $this->item_id;		
		$this->result = $this->item_model->read();

		// get row count
		 $num = $this->result->rowCount();

		return $num;
	}




	/**
	 * Action for GET verb to list resource 
	 * 
	 * @return array   response
	 */
	protected function getAllAction(): array
	{
		// call read action on item model object	
		$this->item_model->id = 0;	
		$result = $this->item_model->read();

		// fetch all PDO result set
  		$data = $result->fetchAll(PDO::FETCH_ASSOC);

		$response = array('message' => 'list resource ','status' => '1', 'data' => $data);

		return $response;	

	}




	/**
	 * Action for GET verb to read an individual resource 
	 * 
	 * @return array   response
	 */
	protected function getAction(): array
	{
		$data = $this->result->fetch(PDO::FETCH_ASSOC);

		$response = array('message' => 'info of individual resource ','status' => '1', 'data' => $data);

		return $response;
	}




	/**
	 * Action for POST verb to create an individual resource 
	 * 
	 * @return array   response
	 */
	protected function postAction(): array
	{
		// empty data, return status as 0
		if(empty($this->data)){			
			$response =	array('message' => 'invalid resource data','status' => '0');
			return $response;			
		}

		$this->item_model->name = htmlspecialchars(strip_tags($this->data['name']));
		$this->item_model->description = htmlspecialchars(strip_tags($this->data['description']));

		// call create action on item model object		
		$result = $this->item_model->create();

		$response = ($result) ? array('message' => 'resource submitted','status' => '1') : array('message' => 'resource not submitted','status' => '0');

		return $response;

	}



	/**
	 * Action for PUT verb to update an individual resource 
	 * 
	 * @return array   response
	 */
	protected function putAction(): array
	{

		// fetch PDO result set
		$data_old = $this->result->fetchAll(PDO::FETCH_ASSOC);			  			

		foreach ($data_old[0] as $key => $value) {
			// values to model
			$this->item_model->$key = (array_key_exists($key, $this->data)) ? htmlspecialchars(strip_tags($this->data[$key])) : null;
		}

		$this->item_model->id = $this->item_id;


		// call update action on item model object		
		$result = $this->item_model->putUpdate();

		$response = ($result) ? array('message' => 'resource updated','status' => '1') : array('message' => 'resource not updated','status' => '0');

		return $response;

	}



	/**
	 * Action for PATCH verb to update an individual resource 
	 * 
	 * @return array   response
	 */
	protected function patchAction(): array
	{
	  	foreach ($this->data as $key => $value) {

			// values to model
			$this->item_model->$key = (!empty($this->data[$key])) ? htmlspecialchars(strip_tags($this->data[$key])) : null;

		}

		// call update action on item model object		
		$result = $this->item_model->update();

		$response = ($result) ? array('message' => 'PATCHES - resource updated','status' => '1') : array('message' => 'PATCHES - resource not updated','status' => '0');

		return $response;
	}




	/**
	 * Action for DELETE verb to delete an individual resource 
	 * 
	 * @return array     response
	 */
	protected function deleteAction(): array
	{
		// call delete action on item model object	
		$this->item_model->id = $this->item_id;

		$result = $this->item_model->delete();

		$response = ($result) ? array('message' => 'resource deleted','status' => '1') : array('message' => 'resource not deleted','status' => '0');

		return $response;
	}


}