<?php

/**
 * Item model to run actions on DB
 */
class ItemModel
{

	/**
	 * to hold database connection object
	 * @var db object
	 */
	private $conn;


	/**
	 * table name
	 * @var string
	 */
	private $table = 'items';



	/**
	 * Class constructor
	 * @param object $db database connection object
	 */
	public function __construct($db)
	{
		$this->conn = $db;

	}




	/**
	 * method to read collection as well as a resource
	 * call from GET verb action
	 *  
	 * @param  int|integer $id 			resource ID
	 * @return object               	PODStatement Object
	 */
	public function read(int $id=0):object
	{
		// query to get collection 
		$query = "SELECT * FROM ". $this->table;

		// add condition to get individual resource
		if($id != 0 ){
			$query .= " WHERE id = :id";
		}

      // prepare statement
      $stmt = $this->conn->prepare($query);

      // bind param
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      // execute query
      $stmt->execute();

      return $stmt;

	}



	/**
	 * method to create a resource 
	 * call from POST verb action
	 * 
	 * @param  array  $data to be created
	 * @return boolean       
	 */
	public function create(array $data):bool
	{
		// query to insert data 
		$query = "INSERT INTO "  . $this->table . " (name, description) VALUES (?, ?)"; 

		$stmt = $this->conn->prepare($query);

		// bind param
		$stmt->bindParam(1, $data['name']);
		$stmt->bindParam(2, $data['description']);
		
		$response = $stmt->execute();

		return $response;
	}




	/**
	 * method to update a resource
	 * call from PUT as well as PATCH verb actions
	 * 
	 * @param  array  $data to be created
	 * @param  int|integer $id 			resource ID
	 * @return boolean       
	 */
	public function update(array $data, int $id):bool
	{

		$set = '';
		$i=0;
		foreach ($data as $key => $value) {
			$set .= ($i>0?',':'').'`'. $key . '`=';
			$set .= ($value === null?'NULL':'"'.$value.'"');
			$i++;
		}

		// query to update data 
		$query = "UPDATE "  . $this->table . " SET ".$set." WHERE id = ?"; 
		
		$stmt = $this->conn->prepare($query);

		$response = $stmt->execute(array($id));  

		return $response;
	}



	/**
	 * method to delete a resource
	 * call from DELETE verb action
	 * 
	 * @param  int|integer $id 		resource ID
	 * @return boolean       
	 */
	public function delete(int $id):bool
	{
		// query to delete data 
		$query = "DELETE FROM `" . $this->table. "` WHERE id = ?";

		$stmt = $this->conn->prepare($query);

		$response = $stmt->execute(array($id));  

		return $response;
	}

	
}