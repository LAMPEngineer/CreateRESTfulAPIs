<?php
	
/*
 *  Items controller to have actions for items
 */

class ItemsController extends MyController implements ControllerInterface
{


	/**
	 * construct initialize db connection object
	 */
	public function __construct(ModelInterface $items_model_interface)
	{
		//Get item model object
		$this->model = $items_model_interface;
	}



}