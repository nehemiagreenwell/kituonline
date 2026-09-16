<?php
/*
*	Program:	shoppingcart.php
*	Desc:		Manages and displays the shopping cart.
*/
session_start();
if(!isset($_SESSION['order_number']) or empty($_SESSION['order_number']))
{
	echo "Shopping Cart is currently empty<br>\n
				<a href='shopcatalog.php'>Continue Shopping</a>\n";
	exit();
}
switch(@$_POST['Cart'])
{
	case "Continue Shopping":
		header("Location: shopcatalog.php");
		break;
	case "Update Cart":
		$connect = connect_to_db("vars.inc");
		$order_number = $_SESSION['order_number'];
		$n = 1;
		/* Update quantities in database */
		foreach($_POST['quantity'] as $field => $value)
		{
			$sql_quant = "UPDATE order_item SET quantity='$value'
				WHERE item_number='$n' AND order_number='$order_number'";
			$result = mysqli_query($connect,$sql_quant)
			or die("sql_quant: ".mysqli_error($connect));
			$n++;
		}
		/* Delete any items with zero quantity */
		$sql_del = "DELETE FROM order_item WHERE quantity='0.0'
				AND order_number='$order_number'";
		$result = mysqli_query($connect,$sql_del)
					or die("sql_del: ".mysqli_error($connect));
		/* Renumber items in database. First put, put items in an array.
		Next,delete all items from the database. Then re-insert items with new
		item numbers */
		$sql_getnew = "SELECT * FROM order_item WHERE order_number='$order_number'";
		$result = mysqli_query($connect,$sql_getnew) or die("sql_getnew: ".mysqli_error($connect));
		$n_rows = mysqli_num_rows($result);
		if($n_rows < 1)
		{
			echo "Shopping Cart is currently empty<br>\n_rows
			<a href='shopcatalog.php'>Continue Shopping</a>\n";
			exit();
		}
		while($row=mysqli_fetch_assoc($result))
		{
			$items_new[] = $row;
		}
		$sql_del2 = "DELETE FROM order_item WHERE order_number='$order_number'";
		$result = mysqli_query($connect,$sql_del2) or die("sql_del2: ".mysqli_error($connect));
		for($i=0;$i<sizeof($items_new);$i++)
		{
			$sql_ord = "INSERT INTO order_item(order_number,item_number,
				catalog_number,quantity,price) VALUES($order_number,$i+1,
				{$items_new[$i]['catalog_number']},{$items_new[$i]['quantity']},
				{$items_new[$i]['price']}";
			$result = mysqli_query($connect,$sql_ord) or die("sql_ord: ".mysqli_error($connect));
		}
		$_SESSION['n_items'] = $i;
		include("fields_cart.inc");
		include("table_page.inc");
		break;
	case "Submit Order":
		header("Location: processorder.php?from=cart");
		exit();
		break;
	default:
		include("fields_cart.inc");
		include("table_page.inc");
	break;
}
?>