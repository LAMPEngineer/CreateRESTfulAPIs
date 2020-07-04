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
	
}