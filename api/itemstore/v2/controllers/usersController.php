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
	
}