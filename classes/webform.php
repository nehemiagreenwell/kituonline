<?php
/*
*	Class:	WebForm
*	Desc:	Class that collects,stores and processes
*			information in an HTML form.
*/
class WebForm
{
	private $form;			//filename
	private $fields;		//filename
	private $data; 			//array
	private $not_required; 	//array
	
	function __construct($form,$fields,$data=NULL)
	{
		if(is_string($form) && is_string($fields))
		{
			$this->form = $form;
			$this->fields = $fields;
		}
		else
		{
			throw new Exception("The first 2 parameters must be filenames");
		}
		if($data == NULL || is_array($data))
		{
			$this->data = $data;
		}
		else 
		{
			throw new Exception("Form data must be passed in an array");
		}
	}
	
	function setFieldsNotRequired($not_required)
	{
		if(!is_array($not_required))
		{
			throw new Exception("Fields must be passed in an array");
		}
		else
		{
			$this->not_required = $not_required;
		}
	}
	
	function displayForm()
	{
		@extract($this->data);
		include($this->fields);
		include($this->form);
	}
	
	function getAllFields()
	{
		return $this->data;
	}
	
	function checkForBlanks()
	{
		if(sizeof($this->data) < 1)
			throw new Exception("No form data available");
		foreach($this->data as $key => $value)
		{
			if($value == "")
			{
				$match = false;
				if(is_array($this->not_required))
				{
					foreach($this->not_required as $field)
					{
						if($field == $key)
						{
							$match = true;
						}
					}
				}
				if($match == false)
				{
					$blanks[] = $key;
				}
			}
		}
		if(isset($blanks))
			return $blanks;
		else
			return TRUE;
	}
	
	function verifyData()
	{
		if(sizeof($this->data) < 1)
			throw new Exception("No form data available.");
		foreach($this->data as $key => $value)
		{
			if(!empty($value))
			{
				if(preg_match("/name/i",$key) && !preg_match("/log/i",$key)
					&& !preg_match("/user/i",$key))
					{
						$result = $this->checkName($value);
						if(is_string($result))
							$errors[$key] = $result;
					}
				if(preg_match("/addr/i",$key) || preg_match("/street/i",$key) ||
						preg_match("/city/i",$key))
						{
							$result = $this->checkAddress($value);
							if(is_string($result))
								$errors[$key] = $result;
						}
				if(preg_match("/email/i",$key))
				{
					$result = $this->checkEmail($value);
					if(is_string($result))
								$errors[$key] = $result;
				}
				if(preg_match("/phone/i",$key) || preg_match("/fax/i",$key))
				{
					$result = $this->checkPhone($value);
					if(is_string($result))
								$errors[$key] = $result;
				}
				if(preg_match("/zip/i",$key))
				{
					$result = $this->checkZip($value);
					if(is_string($result))
								$errors[$key] = $result;
				}
				if(preg_match("/state/i",$key))
				{
					$result = $this->checkState($value);
					if(is_string($result))
								$errors[$key] = $result;
				}
			}
		}
		if(isset($errors))
			return $errors;
		else
			return TRUE;
	}
	
	function trimData()
	{
		foreach($this->data as $key => $value)
		{
			$data[$key] = trim($value);
		}
		$this->data = $data;
	}
	
	function stripTagsFromData()
	{
		foreach($this->data as $key => $value)
		{
			$data[$key] = strip_tags($value);
		}
		$this->data = $data;
	}
	
	function checkName($field)
	{
		if(!preg_match("/^[A-Za-z' -]{1,50}$/",$field))
		{
			return "$field is not a valid address. Please try again.";
		}
		else
			return TRUE;
	}
	
	function checkAddress($field)
	{
		if(!preg_match("/^[A-Za-z0-9.,' -]{1,50}$/",$field))
		{
			return "$field is not a valid address. Please try again.";
		}
		else
			return TRUE;
	}
	
	function checkZip($field)
	{
		if(!preg_match("/^[0-9]{5}(\-[0-9]{4})?/",$field))
		{
			return "$field is not a valid zip code. Please try again.";
		}
		else
			return TRUE;
	}
	
	function checkPhone($field)
	{
		if(!preg_match("/^[0-9)(Xx -]{7,20}$/",$field))
		{
			return "$field is not a valid phone number. Please try again.";
		}
		else
			return TRUE;
	}
	
	function checkEmail($field)
	{
		if(!preg_match("/^.+@.+\\..+$/",$field))
		{
			return "$field is not a valid email address. Please try again.";
		}
		else
			return TRUE;
	}
	
	function checkState($field)
	{
		if(!preg_match("/^[A-Za-z]/",$field))
		{
			return "$field is not a valid state. Please try again.";
		}
		else
			return TRUE;
	}
}
?>