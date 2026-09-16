<?php
/*
*	Program:	shopcatalog.php
*	Desc:		Handles all functions of the online orders application
*				The submit button name is tested to determine which
*				section of the program executes.
*/
require_once("classes/item.php");
require_once("classes/catalog.php");
require_once("classes/shoppingcart.php");
require_once("classes/webform.php");
require_once("classes/webpage.php");
require_once("classes/order.php");
require_once("classes/database.php");
session_start();
if(isset($_POST['Products']) && isset($_POST['interest']))
{
	try
	{
		$catalog = new Catalog("localhost","root","");
		$catalog->selectCatalog("onlineorders");
		$catalog->displayAllofType($_POST['interest'],2);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
}
elseif(isset($_POST['Cart']))
{
	$cart = new ShoppingCart();
	if($_POST['Cart'] == "Update Cart")
	{
		try
		{
			$cart->updateCart($_POST);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			exit();
		}
	}
	elseif($_POST['Cart'] == "Add Items to Cart")
	{
		foreach($_POST as $field => $value)
		{
			if(preg_match("/item/",$field) && $value > 0)
			{
				try
				{
					$cat_no = substr($field,4);
					$item = new Item($cat_no,$value);
					$cart->addItem($item);
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
					exit();
				}
			}
		}
	}
	try
	{
		$cart->displayCart("fields_cart.inc","table_page.inc");
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
}
elseif(isset($_POST['Ship']))
{
	try
	{
		$db = new Database("vars.inc");
		$db->useDatabase("onlineorders");
		$order = new Order($db->getConnection(),"customer_order");
		if(isset($_SESSION['order_number']))
		{
			$order->selectOrder($_SESSION['order_number']);
		}
		else
		{
			$order->createOrder();
		}
		$ord = $order->getOrderNumber();
		$info = $order->getOrderInfo();
		$form = new WebForm("single_form.inc","fields_ship_info.inc",$info);
		$form->displayForm();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
}
elseif(isset($_POST['Summary']))
{
	try
	{
		$form = new WebForm("single_form.inc","fields_ship_info.inc",$_POST);
		$blanks = $form->checkForBlanks();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	if(is_array($blanks))
	{
		$GLOBALS['message'] = "The following required fields were blank.
								Please enter the required information: ";
		foreach($blanks as $value)
		{
			$GLOBALS['message'] .= "$value, ";
		}
		$form->displayForm();
		exit();
	}
	try
	{
		$db = new Database("vars.inc");
		$db->useDatabase("onlineorders");
		$order =
			new Order($db->getConnection(),"customer_order");
		$order->selectOrder($_SESSION['order_number']);
		
	  //Add shipping info to db
		$order->updateOrderInfo($_POST);
		
	  //Add items to db
		$cart = new ShoppingCart();
		$order->addCart($cart);
		
	  // display summary form
	    $order->displayOrderSummary("fields_summary.inc","summary_page.inc");
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}
elseif(isset($_POST['Final']))
{
	if($_POST['Final'] == "Submit Order")
	{
		$db = new Database("vars.inc");
		$db->useDatabase("onlineorders");
		$order = new Order($db->getConnection(),"customer_order");
		$order->selectOrder($_SESSION['order_number']);
		if(processCC())
		{
			$order->setSubmitted();
			$order->sendToShipping();
			$order->sendEmailToCustomer();
			$confirm = new WebPage("confirm_page.inc",$data);
			$confirmpage->displayPage();
		}
		else
		{
			$order->cancel();
			$unapp = new WebPage("not_accepted_page.inc",$data);
			$unapp->displayPage();
			$order->cancel();
			unset($_SESSION['order_number']);
			unset($_SESSION);
			session_destroy();
		}
	}
	else
	{
		$cancel = new WebPage("cancel.inc",$data);
		$cancel->displayPage();
		unset($_SESSION['order_number']);
		unset($_SESSION);
		session_destroy();
	}
}
else
{	try
	{
		$catalog = new Catalog("localhost","root","");
		$catalog->selectCatalog("onlineorders");
		$catalog->displayCategories();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}
?>