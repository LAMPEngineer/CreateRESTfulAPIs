<?php

/**
 * Users model to run actions on DB
 */
class UsersModel extends MyModel implements ModelInterface
{
	/**
	 * to hold database connection object
	 * @var db object
	 */
	protected $conn;


	/**
	 * table name
	 * @var string
	 */
	protected $table;

	// define table properties
	protected $id;
	protected $name;
	protected $email;
	protected $password;

	// setter and getter
	public function setId($id){ $this->id = $id; }
	public function getId(){ return $this->id; }
	
	public function setName($name){ $this->name = $name; }
	public function getName(){ return $this->name; }
	
	public function setEmail($email){ $this->email = $email; }
	public function getEmail(){ return $this->email; }
	
	public function setPassword($password){ $this->password = $password; }
	public function getPassword(){ return $this->password; }


	/**
	 * Class constructor
	 * @param object $db database connection object
	 */
	public function __construct($db)
	{
	  $this->conn = $db;

	  $this->table = 'users';

	}




	/**
	 *  Item table fields with type and 
	 *  corresponding methods for setter/getter 
	 *  
	 * @return [type] array
	 */
	public function getTableFields(): array
	{
		return array('id'   => array('method' => 'id', 'type' => 'INT'),
                     'name' => array('method' => 'name', 'type' => 'STRING'),
                     'email' => array('method' => 'email', 'type' => 'STRING'),
                     'password' => array('method' => 'password', 'type' => 'STRING')
	                );
	}

}