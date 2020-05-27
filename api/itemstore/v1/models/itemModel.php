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
	public $id=0;
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
	 * from GET verb action call
	 *  
	 * @param  int|integer $id 			resource ID
	 * @return object               	PODStatement Object
	 */
	public function read():object
	{
		// query to get collection 
		$query = "SELECT * FROM ". $this->table;

		// add condition to get individual resource
		if($this->id != 0 ){
			$query .= " WHERE id = :id";
		}

      // prepare statement
      $stmt = $this->conn->prepare($query);

      // bind param
      $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

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
	 * call from PATCH verb actions
	 * 
	 * @return boolean       
	 */
	public function update():bool
	{
		// fetch PDO result set
		$result = $this->read();		
		$data_old = $result->fetch(PDO::FETCH_ASSOC);

		$set='';
		$i=0;
		foreach ($data_old as $key => $value) {
		
			if(!empty($this->$key)){
			$set .= ($i>0?',':'').'`'. $key . '`=';			
			$set .= ($this->$key === null?'NULL':'"'.$this->$key.'"');	
			} 
			$i++;
		}

		// query to update data 
		$query = "UPDATE "  . $this->table . " SET ".$set." WHERE id = :id"; 
		//die;
		$stmt = $this->conn->prepare($query);

		// bind param
	    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if($stmt->execute()){
			return true;
		}

		return false;
	}



	/**
	 * method to update a resource
	 * call from PUT verb actions
	 * 
	 * @return boolean       
	 */
	public function putUpdate():bool
	{
		// fetch PDO result set
		$result = $this->read();		
		$data_old = $result->fetch(PDO::FETCH_ASSOC);

		$set='';
		$i=0;
		foreach ($data_old as $key => $value) {
			$set .= ($i>0?',':'').'`'. $key . '`=';
			if(!empty($this->$key)){			
			$set .= ($this->$key === null?'NULL':'"'.$this->$key.'"');	
			} else {
				$set .= 'NULL';
			}

			$i++;
		}

		// query to update data 
		$query = "UPDATE "  . $this->table . " SET ".$set." WHERE id = :id"; 

		$stmt = $this->conn->prepare($query);

		// bind param
	    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if($stmt->execute()){
			return true;
		}

		return false;

	}



	/**
	 * method to delete a resource
	 * call from DELETE verb action
	 * 
	 * @return boolean       
	 */
	public function delete():bool
	{
		// query to delete data 
		$query = "DELETE FROM `" . $this->table. "` WHERE id = :id";

		$stmt = $this->conn->prepare($query);

	    // bind param
	    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if($stmt->execute()){
			return true;
		}

		return false;
	}

	
	
}