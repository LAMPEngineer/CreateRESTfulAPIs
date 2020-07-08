<?php

 /*
  *  Auth controller to have actions for items
  */
class AuthController extends MyController implements ControllerInterface
{

	/**
	 * construct initialize db connection object
	 */
	public function __construct(ModelInterface $auth_model_interface)
	{
		//Get item model object
		$this->model = $auth_model_interface;
	}
	

	/**
	 * to get row count for a resource 
	 * @return int     row count
	 */
	public function getResultSetRowCount(): int
	{
		$num = 1;

/*		// call read action on model object
		$this->model->setId($this->id);

		// get row count
		 $num = $this->model->getResultSetRowCountById();*/

		return $num;
	}


	/**
	 * Action for GET verb to read an individual resource 
	 * 
	 * @return array   response
	 */
	function getAction(): array
	{
		$data = array('id'=>"Auth controller test");

		$response = array('message' => 'Auth resource - get one','status' => '1', 'data' => $data);

		return $response;
	}


	/**
	 * Action for GET verb to list resource 
	 * 
	 * @return array   response
	 */
	function getAllAction(): array
	{
	  // call read action on model object			
	  $data = array("id" => "Auth Testing");

	  $response = array('message' => 'Auth resource - get all','status' => '1', 'data' => $data);

	  return $response;	

	}

}