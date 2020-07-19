<?php

use \Firebase\JWT\JWT;
use MyTraitController as MyTrait;

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



	/**
	 * Login action for POST verb. It generates 
	 * JWT token with user data.
	 * 
	 * @return array  generated token in 'jwt' key
	 */
	public function postLoginAction(): array
	{

		//$users_controller = $this->buildObject('Users');
		$users_controller = MyTrait::buildObject('Users');

		// set requested data to the controller 
		$users_controller->setData($this->data);

		// set requested format to the controller
		$users_controller->setFormat($this->format);

		$response = $users_controller->loginAction();

	    $user_data = $response['user_data'];

  		unset($response['user_data']); // unset user data from responce

  		// generate token
		$token = $this->generateToken($user_data);

		$response['jwt'] = $token;		

		return $response;
	}



	/**
	 * Read action for POST verb. To get jwt token, this method checks :
	 *  i.  post data and
	 *  ii. headers -> 'Authorization'
	 * 
	 * @return array 	keys - 'status', 'message' and 'user_data'
	 */
	public function postReadAction(): array
	{

		$data = (object)$this->data;  // to check post data
		$all_headers = getallheaders(); // to check headers


		if(!empty($data->jwt)){

			$response = $this->readToken($data->jwt);
			$response['message'] .= ' from post data'; 

		} elseif(!empty($all_headers['Authorization'])) {

				$jwt_token = $all_headers['Authorization'];
				$response = $this->readToken($jwt_token);
				$response['message'] .= ' from headers'; 			

		} else {

			$response = array('message' => "ERROR: cann't read jwt token",'status' => '0');
		}

		return $response;
	}



	/**
	 * Generates token with user data using Firebase JWT
	 * encode method. It gets JWT constants from config.
	 * 
	 * 
	 * @param  array $user_data		    user data to generate JWT 
	 * @return string $token 	        generated token           
	 */
	public function generateToken(array $user_data): string
	{

		$iss = env('JWT_ISS');
		$iat = env('JWT_IAT');
		$nbf = $iat + env('JWT_NBF');
		$exp = $iat + env('JWT_EXP');
		$aud = env('JWT_AUD');

		$payload_info = array(
				"iss"  => $iss,
				"iat"  => $iat,
				"nbf"  => $nbf,
				"exp"  => $exp,
				"aud"  => $aud,
				"data" => $user_data
			);

		$secret_key = env('JWT_SECRET');
		$algo = env('JWT_ALGO');

		$token =  JWT::encode($payload_info, $secret_key, $algo); // JWT encode method

		return $token;
	}



	/**
	 * Methot to read JWT token. It reads config to get JWT constants.
	 * 
	 * @param  array $jwt_token	JWT token  
	 * @return array            keys - 'status', 'message' and 'user_data'
	 */
	public function readToken($jwt_token): array
	{

		try{
			$secret_key = env('JWT_SECRET');
			$algo = env('JWT_ALGO');
			
			//JWT decode method
			$decoded_data = JWT::decode($jwt_token, $secret_key, array($algo));
			$data = $decoded_data->data; // user data
			
			$response = array('message' => 'Read jwt token successfully','status' => '1', 'user_data' => $data);

		}catch(Exception $ex){
			$this->throwError('0', $ex->getMessage());
		}

		return $response;

	}


}