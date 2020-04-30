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
	 * construct initialize db connection object
	 */
	public function __construct()
	{
		$db = new DatabaseConfig;
		$this->conn = $db->connect();	
	}

	/**
	 * Action for GET verb
	 * for both individual resource 
	 * as well as a collection
	 * 
	 * @param  Request $request object
	 * @return array            dara
	 */
	public function getAction(Request $request):array
	{

		// get item id from request url
		if(isset($request->url_elements[2])){

			$item_id = (int)$request->url_elements[2];

			// invalid item id
			if(($item_id == 0) or empty($item_id)){

				$data['message'] = 'invalid item id.';

				return $data;

			} else{
				//get a resource
				$data['message'] = 'here is the info of item '. $item_id;
			}			
		} else{
			// get collection
			$data['message'] = 'you want a list of items';
			$item_id = 0;
		}

		//Get item model object
		$items = new ItemModel($this->conn);
		$result = $items->read($item_id);

		// Get row count
  		$num = $result->rowCount();
  		if($num > 0){

  			//fetch PDO result set
  			$data['data'] = $result->fetchAll(PDO::FETCH_ASSOC);

  		} else {

  			$data['message'] = 'no data found';
  		}		

		return $data;
	}

	public function postAction(Request $request)
	{
		$data = $request->parameters;
		$data['message'] = 'This data was submitted';
		return $data;
	}	 

}