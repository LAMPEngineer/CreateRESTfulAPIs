<?php
/**
 *  Parent model to process requests
 *   and have action for child model 
 * 
 */
class MyModel
{

	/**
	 * To get all users
	 * 
	 * @return [array] users array
	 */
	public function getAll(): array 
	{
      // prepare statement
      $stmt = $this->conn->prepare("SELECT * FROM ". $this->table);
      $stmt->execute();
      $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $all_data;

	}



	/**
	 *  To get user by Id
	 *  
	 * @return [array] user array
	 */
	public function getDetailsById(): array
	{	
      $stmt =  $this->getResultSetById();
      $id_data = $stmt->fetch(PDO::FETCH_ASSOC);

      return $id_data;
	}



	/**
	 *  To get result set row count
	 *  
	 * @return [integer] result set count
	 */
	public function getResultSetRowCountById():int 
	{
	  $num = 0;
	  $result = $this->getResultSetById();
	  $num = $result->rowCount();

	  return $num;
	}


}
