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
	 * Item table
	 * @var string
	 */
	private $table = 'items';

	private $name;

	private $description;


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
	 *  
	 * @param  int|integer $item_id 	Item ID
	 * @return object               	PODStatement Object
	 */
	public function read(int $item_id=0):object
	{
		// query to get collection 
		$query = 'SELECT * FROM '. $this->table;

		// add condition to get individual resource
		if($item_id != 0 ){
			$query .= ' WHERE id = ' . $item_id;
		}

      // prepare statement
      $stmt = $this->conn->prepare($query);

      // execute query
      $stmt->execute();

      return $stmt;

	}

	/**
	 * method to create a resource
	 * 
	 * @param  array  $data to be created
	 * @return boolean       
	 */
	public function create(array $data):bool
	{
		// query to insert data 
		$query = "INSERT INTO "  . $this->table . " (name, description) VALUES (?, ?)"; 

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $data['name']);
		$stmt->bindParam(2, $data['description']);
		
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	
}