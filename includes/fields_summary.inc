<?php
/*
*	File: fields_summary.inc
*	Desc: Builds the arrays needed to display a summary
*		  of the order.
*/
$page = array("title" => "The Food Shop Order Summary",
			  "top" => "The Food Shop Order Summary",
			  "top2" => "Order Summary",
			  "table" => "Order Number: ",
			  "bottom" => "Send questions or problems to admin@xFoodShop.com"
			 );
$table_headers = array("Item","Cat No","Food","Amount","Price","Total");
$order_number = $_SESSION['order_number'];
$shipping_rate = .25;
$table_name = $order_number;
?>