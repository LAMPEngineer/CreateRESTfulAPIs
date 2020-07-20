<?php
use MyTraitController as MyTrait;
/**
 * Items model to run actions on DB
 */
class ItemsModel extends MyModel implements ModelInterface
{

	// define table properties
	protected $id;
	protected $name;
	protected $description;
	protected $createdBy;
	protected $updatedBy;
/*	protected $createdAt;
	protected $updatedAt;*/

	// setter and getter
	public function setId($id){ $this->id = $id; }
	public function getId(){ return $this->id; }
	
	public function setName($name){ $this->name = $name; }
	public function getName(){ return $this->name; }
	
	public function setDescription($description){ $this->description = $description; }
	public function getDescription(){ return $this->description; }

	public function setCreatedBy($createdBy){
		echo "in created by***";
		die;
		$response = MyTrait::readHeaderGetUserDataFromJWT();

		if($response['status'] == '1' ){
			$user_id = $response['user_data']['id'];

			$this->created_by = $user_id;
		}
 
	}

	public function getCreatedBy(){ return $this->createdBy; }

	public function setUpdatedAt($updatedBy){ 
		echo "in update by***";
		die;
		$response = MyTrait::readHeaderGetUserDataFromJWT(); 
		if($response['status'] == '1' ){
			$user_id = $response['user_data']['id'];	

			$this->updatedBy = $user_id; 
		}
	}

	public function getUpdetedAt(){ return $this->updatedBy; }
	
/*	public function setCreatedAt($createdAt){ $this->createdAt = $createdAt; }
	public function getCreatedAt(){ return $this->createdAt; }
	
	public function setUpdatedAt($updatedAt){ $this->updatedAt = $updatedAt; }
	public function getUpdetedAt(){ return $this->updatedAt; }*/



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
                     'created_by' => array('method' => 'createdBy', 'type' => 'INT'),
                     'updated_by' => array('method' => 'updatedBy', 'type' => 'INT')
                     /*'created_at' => array('method' => 'createdAt', 'type' => 'STRING'),
                     'updated_at' => array('method' => 'updatedAt', 'type' => 'STRING')*/
	                );
	}
	
	
}