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


	
}