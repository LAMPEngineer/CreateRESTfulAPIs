<?php

use ContainerController as Container;
use \Firebase\JWT\JWT;


 /*
  *  Auth controller to have actions for items
  */
class AuthController extends MyController implements ControllerInterface, AuthInterface
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

	public function generateToken()
	{
		$payload_info = '1234';
		$secret_key = '567';
		$token =  JWT::encode($payload_info, $secret_key, 'HS512');

		return $token;
	}


	public function postLoginAction(): array
	{

		$users_controller = $this->buildObject('Users');

		// set requested data to the controller 
		$users_controller->setData($this->data);

		// set requested format to the controller
		$users_controller->setFormat($this->format);

		$response = $users_controller->loginAction();

		if($response['status']=='1'){
			$token = $this->generateToken();
			$response['token'] = $token;
		}

		return $response;
	}

	private function buildObject(string $action_name): object
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