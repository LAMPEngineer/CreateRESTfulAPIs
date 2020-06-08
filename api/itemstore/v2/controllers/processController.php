<?php

class ProcessController
{

	$request;

	$controller;

	$view;

	public function __construct(RequestInterface $request_interface, ItemInterface $item_interface, FormatInterface $format_interface)
	{
		$this->request_interface = $request_interface;

		$this->controller = $item_interface;

		$this->view = $format_interface;

	}

	public function process()
	{
/*		// request object
		$request = new RequestController();*/

		// route the request to the right place
		echo $controller_name = ucfirst($this->request->url_elements[1]) . 'Controller';

		die;
		if(class_exists($controller_name)){
			
			//$controller = new $controller_name();
			
			// call action
			$result = $this->controller->processRequest($this->request);

			// view format
			$view_name = ucfirst($this->request->format) . 'View';

			if(class_exists($view_name)){

				//$view = new $view_name();
				$this->view->render($result);		
			}

		}

	}

}