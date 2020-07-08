<?php

/**
 * Items model to run actions on DB
 */
class ItemsModel extends MyModel implements ModelInterface
{

	// define table properties
	protected $id;
	protected $name;
	protected $description;
	protected $createdAt;
	protected $updatedAt;

	// setter and getter
	public function setId($id){ $this->id = $id; }
	public function getId(){ return $this->id; }
	
	public function setName($name){ $this->name = $name; }
	public function getName(){ return $this->name; }
	
	public function setDescription($description){ $this->description = $description; }
	public function getDescription(){ return $this->description; }
	
	public function setCreatedAt($createdAt){ $this->created_at = $createdAt; }
	public function getCreatedAt(){ return $this->createdAt; }
	
	public function setUpdatedAt($updatedAt){ $this->updatedAt = $updatedAt; }
	public function getUpdetedAt(){ return $this->updatedAt; }



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
	 *  Table fields with type and 
	 *  corresponding methods for setter/getter 
	 *  
	 * @return [type] array
	 */
	public function getTableFields(): array
	{
		return array('id'   => array('method' => 'id', 'type' => 'INT'),
                     'name' => array('method' => 'name', 'type' => 'STRING'),
                     'description' => array('method' => 'description', 'type' => 'STRING'),
                     'created_at' => array('method' => 'createdAt', 'type' => 'STRING'),
                     'updated_at' => array('method' => 'updatedAt', 'type' => 'STRING')
	                );
	}
	
	
}