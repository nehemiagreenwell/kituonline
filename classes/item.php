<?php
/*
*	File: 	item.class
*	Desc:	Represents an item in the order
*/
class Item
{
	private $catalog_number;
	private $quantity;
	private $name;
	private $price;
	
	function __construct($cat_no,$quantity)
	{
		if(is_string($cat_no) && is_numeric($quantity))
		{
			$this->quantity = $quantity;
			$this->catalog_number = $cat_no;
			$cat = new Catalog("localhost","root","");
			$cat->selectCatalog("onlineorders");
			$this->name = $cat->getName($cat_no);
			$this->price = $cat->getPrice($cat_no);
		}
		else
		{
			throw new Exception("Parameter is not a valid catalog number and quantity");
		}
	}
	
	function getCatalogNumber()
	{
		return $this->catalog_number;
	}
	
	function getQuantity()
	{
		return $this->quantity;
	}
	
	function getPrice()
	{
		return $this->price;
	}
	
	function getName()
	{
		return $this->name;
	}
}
?>