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
		// create query to get collection 
		$query = 'SELECT * FROM '. $this->table;

		// add condition to get individual resource
		if($item_id != 0 ){
			$query .= ' WHERE id = ' . $item_id;
		}

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;

	}
	
}