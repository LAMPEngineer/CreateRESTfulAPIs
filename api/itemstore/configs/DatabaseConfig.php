<?php
/*
 *  DB connection with PDO
 */
class DatabaseConfig 
{
	//DB params
	private $host = 'localhost';
	private $db_name = 'rest_api_app';
	private $username = 'root';
	private $password = '';
	private $conn;

	//DB connect
	public function connect()
	{
		$this->conn = null;

		try{
			//PDO object
			$this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			echo 'Connection Error: '.$e->getMessage();			
		}

		return $this->conn;

	}
}
