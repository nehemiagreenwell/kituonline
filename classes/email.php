<?php
/*
*	Class:	Email
*	Desc:	Stores an email message
*/
class Email
{
	private $message;
	private $addr;
	private $subj;
	
	function setMessage($message)
	{
		if(!is_string($message))
		{
			throw new Exception("Message must be a string");
		}
		else
		{
			$this->message = $message;
			return TRUE;
		}
	}
	
	function setAddr($addr)
	{
		if(!is_string($addr))
			// Must use RegEx to check address 
		{
			throw new Exception("Address must be a string.");
			return FALSE;
		}
		else
		{
			$this->addr = $addr;
			return TRUE;
		}
	}
	
	function setSubj($subj)
	{
		if(!is_string($subj))
		{
			throw new Exception("Subject must be a string");
		}
		else
		{
			$this->subj = $subj;
			return TRUE;
		}
	}
	
	function sendEmail()
	{
		if(!empty($this->subj) && !empty($this->addr) && !empty($this->message))
		{
			if(!mail($this->addr,$this->subj,$this->message))
				throw new Exception("Email could not be sent.");
			else
				return TRUE;
		}
		else
		{
			throw new Exception("Subject, Address, and message are required.
								One or more is missing");
			return FALSE;
		}
	}
}