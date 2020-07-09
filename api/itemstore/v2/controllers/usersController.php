<?php
/*
 *  Items controller to have actions for items
 */

class UsersController extends MyController implements ControllerInterface
{


	/**
	 * construct initialize db connection object
	 */
	public function __construct(ModelInterface $users_model_interface)
	{
		//Get item model object
		$this->model = $users_model_interface;
	}


	/**
	 * function to check email id, if not present
	 * sends back the email else theow error
	 * 
	 * @param  string $email 
	 * @return [mixed]  
	 */
	protected function checkForEmailId(string $email)
	{
		if(($this->model->checkEmail($email)) == 0)
		{			
			return $email;

		} else {

			$this->throwError('0', 'Email id already exists.');
		}		
	}



	/**
	 * login action for POST verb 
	 * 
	 * @return array response with status
	 */
	public function postLoginAction(): array
	{
		$data = (object)$this->data;

		if(!empty($data->email) && !empty($data->password)){

			if($this->model->checkEmail($data->email) != 0){

			  	 // validation
			    $setter_value = $this->validateParameter('email', $data->email, false);
			  	
			    // values to model
			    $this->model->setEmail($setter_value);
			
				// call insert action on model object		
				$user_data = (object)$this->model->login();

				if(password_verify($data->password, $user_data->password)){			

					$response = array('message' => 'Login successful', 'status' => '1');

				}else{
					$this->throwError('0', 'Invalid credentials');
				}

			}else{
				$this->throwError('0', 'Invalid credentials');
			}

		}else{
			$this->throwError('0', 'All data needed');
		}

		return $response;
	}


	public function postLogoutAction()
	{
		$data = $this->data;

		$response = array('message' => 'Auth - Post Logout *** Action', 'status' => '1', 'data' => $data);

		return $response;
	}
	
}