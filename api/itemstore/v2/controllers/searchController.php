<?php
	use MyTraitController as MyTrait;
	
/*
 *  Search controller 
 */

class SearchController 
{
	private $controller;

	/**
	 * initialize controller object
	 */
	public function __construct(ControllerInterface $controller_object)
	{
		$this->controller = $controller_object;
	}

}