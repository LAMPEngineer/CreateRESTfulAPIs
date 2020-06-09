<?php
/**
 *  Parent controller to process requests
 *   and have action for child controller 
 * 
 */

use RequestController as Request;

class MyController
{

	/**
	 *  Validate parameter for string, int, boolean etc.
	 *  and also htmlspecialchars & strip_tags
	 *  
	 * @param  [string]  $fieldName 
	 * @param  [string]  $value     
	 * @param  [boolean] $required  
	 * 
	 * @return [string]  $value          
	 */
	public function validateParameter($fieldName, $value, $required = true)
	{
		$item_table_fields = $this->item_model->getItemTableFields();

		if($required == true && empty($value) == true){
			return array('message' => $fieldName.' parameter is required.','status' => '0');
		}

		switch ($item_table_fields[$fieldName]['type']) {
			
			case 'BOOLEAN':
				if (!is_bool($value)) {

					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'INT':
				if (!is_int($value)) {

					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'INTEGER':
				if (!is_numeric($value)) {
					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}
				break;

			case 'STRING':
				if (!is_string($value)) {
					$this->throwError('0', 'Data type is not valid for '. $fieldName);
				}				
				break;
			
			default:
				$this->throwError('0', 'Data type is not valid for '. $fieldName);
				break;
		}

		return htmlspecialchars(strip_tags($value));
		
	}


	/**
	 * To throw error if occure. It uses view
	 *  format i.e. json to send message
	 * 
	 * @param  [string] $code    
	 * @param  [string] $message 
	 * 
	 * @return exit         
	 */
	public function throwError($code, $message)
	{
		$content = array('message' => $message,'status' => $code);

		// view format
		$view_name = ucfirst($this->format) . 'View';

		if(class_exists($view_name)){

			$view = new $view_name();
			$view->render($content);		
		}

		exit;		
	}

}
