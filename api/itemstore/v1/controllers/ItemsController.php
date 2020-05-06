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
	 * Action for GET verb
	 * for both individual resource 
	 * as well as a collection
	 * 
	 * @param  Request $request object
	 * @return array            response
	 */
	public function getAction(Request $request):array
	{

		// get item id from request url
		if(isset($request->url_elements[5])){

			$item_id = (int)$request->url_elements[5];

			// invalid item id
			if(($item_id == 0) or empty($item_id)){

				$data['message'] = 'ERROR: Bad Request';
				$data['status'] = '0';

				return $data;

			} else{
				//get a resource
				$data['message'] = 'here is the info of item '. $item_id;
			}			
		} else{
			// get collection
			$data['message'] = 'you want a list of items';
			$item_id = '0';
		}

		// call read action on item model object		
		$result = $this->item_model->read($item_id);

		// get row count
  		$num = $result->rowCount();
  		if($num > 0){

  			// fetch PDO result set
  			$data['data'] = $result->fetchAll(PDO::FETCH_ASSOC);
  			$data['status'] = '1';

  		} else {

  			$data['message'] = 'no data found';
  			$data['status'] = '0';
  		}		

		return $data;
	}

	/**
	 * Action for POST verb
	 * for both individual resource 
	 * as well as a collection
	 * 
	 * @param  Request $request object
	 * @return array            response
	 */
	public function postAction(Request $request):array
	{
		$data = $request->parameters;

		// item id set hence error
		if(isset($request->url_elements[5])){

			$response['message'] = 'ERROR: Bad Request';
			$response['status'] = '0';
			return $response;
		}

		// empty data, return status as 0
		if(empty($data)){

			$data['message'] = 'invalid item data';
			$data['status'] = '0';
			return $data;
		}

		$data_clean['name'] = htmlspecialchars(strip_tags($data['name']));
		$data_clean['description'] = htmlspecialchars(strip_tags($data['description']));

		// call create action on item model object		
		$result = $this->item_model->create($data_clean);

		if($result){
			$data_clean['message'] = 'item was submitted';
			$data_clean['status'] = '1';
			return $data_clean;
		} else{
			$data['message'] = 'item was not submitted';
			$data['status'] = '0';
			return $data;
		}
		
	}

	/**
	 * Action for PUT verb
	 * for both individual resource 
	 * as well as a collection
	 * 
	 * @param  Request $request object
	 * @return array            response
	 */
	public function putAction(Request $request):array
	{
		$data = $request->parameters;
		
		// item id from request url
		if(isset($request->url_elements[5])){

			$item_id = (int)$request->url_elements[5];

			// invalid item id
			if(($item_id == 0) or empty($item_id)){

				$response['message'] = 'ERROR: Bad Request';
				$response['status'] = '0';

				return $response;

			} else{		

				// call read action on item model object		
				$result = $this->item_model->read($item_id);

				// get row count
		  		$num = $result->rowCount();

		  		if($num > 0){		  			
		  			//$data['message'] = 'you want to update item '. $item_id;

		  			$data_clean['id'] = $item_id;

		  			$data_clean['name'] = (!empty($data['name'])) ? htmlspecialchars(strip_tags($data['name'])) : null;

					$data_clean['description'] = (!empty($data['description'])) ? htmlspecialchars(strip_tags($data['description'])) : null;

					// call update action on item model object		
					$result = $this->item_model->update($data_clean, $item_id);

					if($result){
						$response['message'] = 'item id ' . $item_id . ' : updated';
						$response['status'] = '1';
						return $response;
					} else{
						$response['message'] = 'item id ' . $item_id . ' : not updated';
						$response['status'] = '0';
						return $response;
					}
		  			
		  		} else {

		  			$response['message'] = 'ERROR: item not found';
		  			$response['status'] = '0';
		  		}
			  		
			}			
		} else{
			// get collection
			$response['message'] = 'Bulk update curently not available!';
			$response['status'] = '0';
		}		

		return $response;
	}


	/**
	 * Action for PATCH verb
	 * for individual resource 
	 * 
	 * @param  Request $request object
	 * @return array            response
	 */
	public function patchAction(Request $request):array
	{
		$data = $request->parameters;
		
		// item id from request url
		if(isset($request->url_elements[5])){

			$item_id = (int)$request->url_elements[5];

			// invalid item id
			if(($item_id == 0) or empty($item_id)){

				$response['message'] = 'ERROR: Bad Request';
				$response['status'] = '0';

				return $response;

			} else{		

				// call read action on item model object		
				$result = $this->item_model->read($item_id);

				// get row count
		  		$num = $result->rowCount();

		  		if($num > 0){		  			
		  			
		  			// fetch PDO result set
  					$data_old = $result->fetchAll(PDO::FETCH_ASSOC);

		  			$data_clean['id'] = $item_id;

		  			foreach ($data_old[0] as $key => $value) {

		  				if(array_key_exists($key, $data)) {

		  					$data_clean[$key] = htmlspecialchars(strip_tags($data[$key]));
		  					
		  				} else{

		  					$data_clean[$key] = $value;
		  				}
		  			}

					// call update action on item model object		
					$result = $this->item_model->update($data_clean, $item_id);

					if($result){
						$response['message'] = 'PATCHES - item id ' . $item_id . ' : updated';
						$response['status'] = '1';
						return $response;
					} else{
						$response['message'] = 'PATCHES  - item id ' . $item_id . ' : not updated';
						$response['status'] = '0';
						return $response;
					}
		  			
		  		} else {

		  			$response['message'] = 'ERROR: item not found';
		  			$response['status'] = '0';
		  		}
			  		
			}			
		} else{
			// get collection
			$response['message'] = 'ERROR: Bad Request';
			$response['status'] = '0';
		}		

		return $response;
	}



	/**
	 * Action for DELETE verb
	 * for both individual resource 
	 * as well as a collection
	 * 
	 * @param  Request $request object
	 * @return array            response
	 */
	public function deleteAction(Request $request):array
	{
		// item id from request url
		if(isset($request->url_elements[5])){

			$item_id = (int)$request->url_elements[5];

			// invalid item id
			if(($item_id == 0) or empty($item_id)){

				$response = array('message' => 'ERROR: Bad Request','status' => '0');

			} else{

				// call read action on item model object		
				$result = $this->item_model->read($item_id);

				// get row count
		  		$num = $result->rowCount();

		  		if($num > 0){
			  		// call delete action on item model object		
					$result = $this->item_model->delete($item_id);

					$response = ($result) ? array('message' => 'item deleted','status' => '1') : array('message' => 'item not deleted','status' => '0');

		  		} else {

		  			$response = array('message' => 'ERROR: item id not found','status' => '0');
		  		}

			}			
		} else {
			// response for bulk delete
			$response = array('message' => 'Bulk delete curently not available!','status' => '0');
		}

		return $response;
	} 

}