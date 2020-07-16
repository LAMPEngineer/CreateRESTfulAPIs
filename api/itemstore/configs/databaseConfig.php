<?php

//use appConfig;

/*
 *  DB connection with PDO
 */
class DatabaseConfig 
{

	//private $configs;
	private $conn;

/*	public function __construct($configs)
	{
		$this->configs = $configs;
	}
*/
	//DB connect
	public function connect()
	{
		$this->conn = null;

		try{
			//PDO object
			$this->conn = new PDO('mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME'),env('DB_USERNAME'), env('DB_PASSWORD'));

			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			echo 'Connection Error: '.$e->getMessage();			
		}

		return $this->conn;

	}
}
