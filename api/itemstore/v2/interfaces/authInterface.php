<?php
/**
 *  
 * 
 * Describes the interface of auth that exposes methods to read its entries.
 * 
 */

interface AuthInterface
{
	
	public function generateToken();

	public function postLoginAction(): array;

}
