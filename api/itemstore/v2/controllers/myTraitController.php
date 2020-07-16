<?php

use ContainerController as Container;

trait MyTraitController
{
	/**
	 * method to build controller object
	 * 
	 * @param  string $action_name      the controller
	 * @return object              		controller object
	 */

	public function buildObject(string $action_name): object
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