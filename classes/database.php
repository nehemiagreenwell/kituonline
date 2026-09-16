<?php
/*
*	File:	database.class
*	Desc:	Class that connects to a MySQL database.
*/
class Database
{
	private $cxn;	// database connection object
	private $database_name;
	private $host;
	private $user;
	private $password;
	
	function __construct($filename)
	{
		include("$filename");
		if(!$this->cxn = new mysqli($host,$user,$password))
		{
			throw new Exception("Database is not available. Try again later.");
			email("dbadmin@ourplace.com","DB Problem","MySQL is not responding. "
					.$this->cxn->error());
			exit();
		}
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
	}
	
	function useDatabase($dbname)
	{
		if(!$result = $this->cxn->query("SHOW DATABASES"))
		{
			throw new Exception("Database is not available. Try again later");
			email("dbadmin@ourplace.com","DB Problem","MySQL is not responding. "
					.$this->cxn->error());
			exit();
		}
		else
		{
			while($row = $result->fetch_row())
			{
				$databases[] = $row[0];
			}
		}
		if(in_array($dbname,$databases) || in_array(strtolower($dbname),$databases))
		{
			$this->database_name = $dbname;
			$this->cxn->select_db($dbname);
			return TRUE;
		}
		else
		{
			throw new Exception("Database $dbname not found.");
			return FALSE;
		}
	}
	
	function getConnection()
	{
		return $this->cxn;
	}
}
?>