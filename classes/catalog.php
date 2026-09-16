<?php
/*
*	Class: 	Catalog
*	Desc:	Class that contains product information.
*/
class Catalog
{
	private $cxn;
	private $catalog_name;
	private $host;
	private $user;
	private $password;
	
	function __construct($host,$username,$password)
	{
		try 
		{
			$this->cxn = new mysqli($host,$username,$password);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$this->host = $host;
		$this->user = $username;
		$this->password = $password;
		
	}
	
	function selectCatalog($database)
	{
		$db = $this->cxn->select_db($database);
		if(mysqli_errno($this->cxn))
		{
			if(mysqli_errno($this->cxn) == 1049)
			{
				throw new Exception("Database does not exist.");
				exit();
			}
			else
			{
				throw new Exception("Database is not available. Try again later.");
				exit();
			}
		}
		$this->catalog_name = $database;
	}
	
	function getCategoriesAndTypes()
	{
		$sql = "SELECT DISTINCT category,type FROM food ORDER BY category,type";
		if(!$result = $this->cxn->query($sql))
		{
			throw new Exception(mysqli_error($this->cxn));
			exit();
		}
		while($row = $result->fetch_array())
		{
			$array_cat_type[$row['category']][] = $row['type'];
		}
		return $array_cat_type;
	}
	
	function getAllofType($type)
	{
		if(is_string($type))
		{
			$sql = "SELECT * FROM food WHERE type = '$type' ORDER BY name";
		}
		else
		{
			throw new Exception("$type is not a type.");
			exit();
		}
		if(!$result = $this->cxn->query($sql))
		{
			throw new Exception(mysqli_error($this->cxn));
			exit();
		}
		$n=1;
		while($row = $result->fetch_object())
		{
			$array_all[$n] = $row;
			$n++;
		}
		return $array_all;
	}
	
	function displayCategories()
	{
		$food_categories = $this->getCategoriesAndTypes();
		include("./includes/fields_index_page.inc");
		include("./includes/catalog_index_page.inc");
	}
	
	function displayAllofType($type,$page)
	{
		if(is_string($type))
		{
			$all = $this->getAllofType($type);
		}
		else
		{
			throw new Exception("$type is not a type.");
			exit();
		}
		if(is_int($page))
		{
			$n_per_page = $page;
		}
		else
		{
			throw new Exception("$page is not an integer.");
			exit();
		}
		$n_products = sizeof($all);
		if(isset($_POST['n_end']))
		{
			if($_POST['Products'] == "Previous")
			{
				$n_start = $_POST['n_end'] - ($n_per_page);
			}
			else
			{
				$n_start = $_POST['n_end'] + 1;
			}
		}
		else
		{
			$n_start = 1;
		}
		$n_end = $n_start + $n_per_page -1;
		if($n_end >= $n_products)
		{
			$n_end = $n_products;
		}
		include("./includes/fields_product_page.inc");
		include("./includes/shopping_product_page.inc");
	}
	
	function getName($catalog_number)
	{
		if(preg_match("/[0-9]*/",$catalog_number))
		{
			$sql = "SELECT name,type FROM food WHERE catalog_number='$catalog_number'";
		}
		else
		{
			throw new Exception("$catalog_number is not a catalog number.");
			exit();
		}
		if(!$result = $this->cxn->query($sql))
		{
			throw new Exception(mysqli_error($this->cxn));
			exit();
		}
		$name = $result->fetch_assoc();
		return "{$name['name']}"." {$name['type']}";
	}
	
	function getPrice($catalog_number)
	{
		if(preg_match("/[0-9]*/",$catalog_number))
		{
			$sql = "SELECT price FROM food WHERE catalog_number='$catalog_number'";
		}
		else
		{
			throw new Exception("$catalog_number is not a number.");
			exit();
		}
		if(!$result = $this->cxn->query($sql))
		{
			throw new Exception(mysqli_error($this->connection));
			exit();
		}
		$price = $result->fetch_assoc();
		return "{$price['price']}";
	}
}
?>