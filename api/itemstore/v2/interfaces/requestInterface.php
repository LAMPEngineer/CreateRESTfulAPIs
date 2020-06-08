<?php

interface RequestInterface
{

	/**
	 * request url elements
	 * @var string
	 */
	public $url_elements;

	/**
	 * HTTP verbs viz. GET, POST, PUT, DELETE
	 * @var string
	 */
	public $verb;

	/**
	 * request parameters
	 * @var array
	 */
	public $parameters;

	/**
	 * Parse incoming parameters	
	 */
	public function parseIncomingParams();

	
	
}