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
	private $id;
	private $name;
	private $email;
	private $password;

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
	 * method to create a resource 
	 * call from POST verb action
	 * 
	 * @return boolean       
	 */
	public function insert():bool
	{
		// get model table fields
		$item_table_fields = $this->getUserTableFields();

		$set='';
		$i=0;
		$bind_param_array = array();

		// make SET and $bindParam variable
		foreach ($item_table_fields as $key => $value) {

			if (!empty($this->$key)) {

				$set .= ($i>0?',':'').'`'. $key . '`= :' .$key;	

				$bind_param_array[$key] =  $this->$key ;

				$i++;
			}
		}

		// insert query
		$query = "INSERT INTO "  . $this->table . " SET " . $set;

		$stmt = $this->conn->prepare($query);

		// bind param 
		foreach ($bind_param_array as $key => &$value) {
			$stmt->bindParam($key, $value);	
		}

		if($stmt->execute()){
			return true;
		}

		return false;

	}

	public function getResultSetById():object
	{
		
	}

	public function update($request_verb):bool
	{

	}

	public function delete():bool
	{

	}



	/**
	 *  Item table fields with type and 
	 *  corresponding methods for setter/getter 
	 *  
	 * @return [type] array
	 */
	public function getUserTableFields(): array
	{
		return array('id'   => array('method' => 'id', 'type' => 'INT'),
                     'name' => array('method' => 'name', 'type' => 'STRING'),
                     'email' => array('method' => 'email', 'type' => 'STRING'),
                     'password' => array('method' => 'password', 'type' => 'STRING')
	                );
	}

}