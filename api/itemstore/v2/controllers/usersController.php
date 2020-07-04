<?php
/*
 *  Items controller to have actions for items
 */

class UsersController extends MyController //implements ControllerInterface
{
		/**
	 * to hold item model object
	 * @var object
	 */
	protected $user_model;

	/**
	 * to hold request data
	 * @var array
	 */
	protected $data= array();

	/**
	 * resourse ID
	 * @var int
	 */
	protected $user_id;



	/**
	 * construct initialize db connection object
	 */
	public function __construct(ModelInterface $users_model_interface)
	{
		//Get item model object
		$this->user_model = $users_model_interface;
	}



	
}