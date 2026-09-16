<?php
/*
*	Class:	WebPage
*	Desc:	Class that stores the information needed to display a Web page
*/
class WebPage
{
	private $filename;
	private $data;
	
	function __construct($filename,$data=NULL)
	{
		if(is_string($filename))
		{
			$this->filename = $filename;
		}
		else
		{
			throw new Exception("Filename must be a string");
		}
		if($data == NULL || is_array($data))
		{
			$this->data = $data;
		}
		else
		{
			throw new Exception("Data must be passed in an array");
		}
	}
	
	function displayPage()
	{
		@extract($this->data);
		include($this->filename);
	}
}
?>