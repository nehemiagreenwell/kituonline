<?php
/*
*	File:	order.class
*	Desc:	Class that holds orders
*/
class Order
{
	private $order_number;
	private $order_info;
	private $order_items=array();
	private $cxn;
	private $table;
	
	function __construct(mysqli $cxn,$table)
	{
		$this->cxn = $cxn;
		if(is_string($table))
		{
			$this->table = $table;
		}
		else
		{
			throw new Exception("$table is not a valid table name.");
		}
	}
	
	function createOrder()
	{
		$today = date("Y-m-d");
		$sql = "INSERT INTO $this->table(order_date) VALUES('$today')";
		if($result = $this->cxn->query($sql))
		{
			$this->order_number = $this->cxn->insert_id;
			$_SESSION['order_number'] = $this->order_number;
		}
		else
		{
			throw new Exception("Database is not available. Try again later");
		}
	}
	
	function getOrderNumber()
	{
		return $this->order_number;
	}
	
	function addCart(ShoppingCart $cart)
	{
		foreach($cart->getAllItems() as $n => $item)
		{
			$cat_no = $item->getCatalogNumber();
			$quantity = $item->getQuantity();
			$price = $item->getPrice();
			$sql = "INSERT INTO order_item(order_number,catalog_number,quantity,item_number,price)
						VALUES($this->order_number,$cat_no,$quantity,($n+1),$price)";
			$result = $this->cxn->query($sql);
		}
	}
	
	function selectOrder($order_number)
	{
		if(is_int($order_number))
		{
			$this->order_number = $order_number;
		}
		else
		{
			throw new Exception("$order_number is not an integer");
		}
	}
	
	function getOrderInfo()
	{
		$sql = "SELECT * FROM $this->table WHERE order_number='$this->order_number'";
		if($result = $this->cxn->query($sql))
		{
			return $result->fetch_assoc();
		}
		else
		{
			throw new Exception("Database is not available. Try again later");
		}
	}
	
	function getItemInfo()
	{
		$sql = "SELECT item_number,catalog_number,quantity,price FROM order_item
					WHERE order_number='$this->order_number'";
		if($result = $this->cxn->query($sql))
		{
			$n=1;
			while($row=$result->fetch_assoc())
			{
				foreach($row as $field => $value)
				{
					$item[$n][$field] = $value;
				}
				$cat = new Catalog("vars.inc");
				$cat->selectCatalog("onlineorders");
				$item[$n]['name']=$cat->getName($item[$n]['catalog_number']);
				$n++;
			}
			return $item;
		}
		else
		{
			throw new Exception("Database is not available. Try again later");
		}
	}
	
	function updateOrderInfo($data)
	{
		if(!is_array($data))
		{
			throw new Exception("Data must be in an array.");
			exit();
		}
		$sql = "UPDATE $this->table SET ";
		foreach($data as $field => $value)
		{
			if(preg_match("/ship/",$field) || $field == "phone" || $field == "email")
			{
				$data_array[] = "$field='$value'";
			}
		}
		$sql .= implode($data_array,',');
		$sql .= "WHERE order_number='$this->order_number'";
		if(!$result = $this->cxn->query($sql))
		{
			throw new Exception("Database is not available. Try again later");
		}
		return TRUE;
	}
	
	function displayOrderSummary($field_info,$field_page)
	{
		$data = $this->getOrderInfo();
		$items = $this->getItemInfo();
		extract($data);
		if(is_string($field_info) && is_string($field_page))
		{
			include($field_info);
			include($field_page);
		}
		else
		{
			throw new Exception("Requires two valid filenames.");
		}
	}
}
?>