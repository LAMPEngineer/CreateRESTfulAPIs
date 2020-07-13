<?php

interface RequestInterface
{

	/**
	 * Parse incoming parameters	
	 */
	public function parseIncomingParams();

	/**
	 * function to check and process all request
	 * and passon to the controller
	 * 
	 * @param  ControllerInterface $controller object of Controller
	 * @return array response
	 */
	function processRequest(ControllerInterface $controller):array;	


	/**
	 * function to process auth requests
	 * 
	 * @param  ControllerInterface $controller 
	 * @param  string $auth_action 
	 * @return array                          
	 */
	public function processAuthRequest(ControllerInterface $controller, string $auth_action): array;


	/**
	 * function to send response in defined format
	 * by default is json format
	 * 
	 * @param  array $result 
	 * @return exit
	 */
	public function sendResponse(array $result);
	
}