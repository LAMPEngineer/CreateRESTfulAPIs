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
	private $table;

	// define properties
	public $id;
	public $name;
	public $description;
	public $created_at;
	public $updated_at;



	/**
	 * Class constructor
	 * @param object $db database connection object
	 */
	public function __construct($db)
	{
		$this->conn = $db;

		$this->table = 'items';

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
	 * @return boolean       
	 */
	public function create():bool
	{

		$query = "INSERT INTO "  . $this->table . " SET name = ?, description = ? ";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->name, PDO::PARAM_STR);
		$stmt->bindParam(2, $this->description, PDO::PARAM_STR);

		if($stmt->execute()){
			return true;
		}

		return false;

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
	 * @return boolean       
	 */
	//public function delete(int $id):bool
	public function delete():bool
	{
		// query to delete data 
		$query = "DELETE FROM `" . $this->table. "` WHERE id = ?";

		$stmt = $this->conn->prepare($query);

		$response = $stmt->execute(array($this->id));  

		return $response;
	}

	
}