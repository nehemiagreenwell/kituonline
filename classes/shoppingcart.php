<?php
/*
*	File:	shoppingcart.class
*	Desc:	Creates a shopping cart--a structure that holds items
*/
class ShoppingCart
{
	private $items = array();
	private $message;
	private $n_items = 0;
	
	function __construct()
	{
		if(isset($_SESSION['items']))
		{
			$this->items = $_SESSION['items'];
			$this->n_items = sizeof($this->items);
		}
		$this->message = "Shopping Cart contains {$this->n_items} items.";
	}
	
	function addItem(Item $item)
	{
		$this->items[] = $item;
		$_SESSION['items'] = $this->items;
		$this->n_items++;
		$this->message = "Shopping Cart contains {$this->n_items} items.";
	}
	
	function getAllItems()
	{
		return $this->items;
	}
	
	function getMessage()
	{
		return $this->message;
	}
	
	function displayCart($file_fields,$file_page)
	{
		include("./includes/".$file_fields);
		include("./includes/".$file_page);
	}
	
	function updateCart($new_array)
	{
		if(is_array($new_array))
		{
			foreach($new_array as $field => $value)
			{
				if(preg_match("/item/",$field) && $value > 0)
				{
					$cat_no = substr($field,4);
					$items_new[] = new Item($cat_no,$value);
				}
			}
			$this->items = @$items_new;
			$_SESSION['items'] = $this->items;
			$this->n_items = sizeof($this->items);
			$this->message = "Shopping Cart contains {$this->n_items} items.";
		}
		else
		{
			throw new Exception("Parameter is not an array");
		}
	}
}
?>