<?php
/*
	Program:	processorder.php
	Desc:		Processes order when it's been submitted.
*/
session_start();
include("functions_main.inc");
if(!isset($_SESSION['order_number']))
{
	echo "No order number found<br>\n";
	header("Location: shopcatalog.php");
	exit();
}
if(@$_GET['from'] == "cart")
{
	include("fields_ship_info.inc");
	include("single_form.inc");
	exit();
}
elseif(isset($_POST['summary']))
{
	foreach($_POST as $field => $value)
	{
		if($value == "")
		{
			$blanks[] = $field;
		}
	}
	if(isset($blanks))
	{
		$message = "The following fields are blank. Please enter the required information: ";
		foreach($blanks as $value)
		{
			$message .= "$value, ";
		}
		extract($_POST);
		include("fields_ship_info.inc");
		include("single_form.inc");
		exit();
	}
	foreach($_POST as $field => $value)
	{
		if($field != "Summary")
		{
			if(preg_match("/name/i",$field))
			{
				if(!preg_match("/^[a-zA-Z' -]{1,50}$/",$value))
					{
						$errors[] = "$value is not a valid name.";
					}
			}
			if(preg_match("/street/i",$field) or preg_match("/addr/i",$field) or preg_match("/city/i",$field))
			{
				if(!preg_match("/^[a-zA-Z0-9.,' -]{1,50}$/",$value))
					{
						$errors[] "$value is not a valid address or city.";
					}
			}
			if(preg_match("/state/i",$field))
			{
				if(!preg_match("/[a-zA-Z]/",$value))
				{
					$errors[] = "$value is not a valid state.";
				}
			}
			if(preg_match("/email/i",$field))
			{
				if(!preg_match("^.+@.+\\..+$",$value))
				{
					$errors[] = "$value is not a valid email address.";
				}
			}
			if(preg_match("/zip/i",$field))
			{
				if(!preg_match("/^[0-9]{5,5}(\-[0-9]{4,4})?$/",$value))
					{
						$errors[] = "$value is not a valid zipcode. ";
					}
			}
			if(preg_match("/phone/i",$field))
			{
				if(!preg_match("/^[0-9) (xX -]{7,20}$/",$value))
					{
						$erros[] = "$value is not a valid phone number. "; 
					}
			}
			if(preg_match("/cc_number/i",$field))
			{
				$value = trim($value);
				$value = preg_replace(' ','',$value);
				$value = preg_replace('_','',$value);
				$_POST['cc_type'] = $value;
				if($_POST['cc_type'] == "visa")
				{
					if(!preg_match("/^[4]{1,1}[0-9]{12,15}$/",$value))
						{
							$errors[] = "$value is not a valid Visa number. ";
						}
				}
				elseif($_POST['cc_type'] == "mc")
				{
					if(!preg_match("/^[5]{1,1}[0-9]{15,15}$/",$value))
						{
							$errors[] = "$value is not a valid MasterCard number. ";
						}
				}
				else
				{
					if(!preg_match("/^[3]{1,1}[0-9]{14,14}$/",$value))
						{
							$errors[] = "$value is not a valid American Express number. ";
						}
				}
			}
			$$field = strip_tags(trim($value));
		}
	}
	if(@is_array($errors))
	{
		$message = "";
		foreach($errors as $value)
		{
			$message .= $value . " Please try again<br />";
		}
		include("fields_ship_info.inc");
		include("single_form.inc");
		exit();
	}
	/* Process data when all fields are correct */
	foreach($_POST as $field => $value)
	{
		if(!preg_match("/cc_/",$field) && $field != "Summary")
		{
			$value = addslashes($value);
			$updates[] = "$field = '$value'";
		}
	}
	$update_string = implode($updates," ");
	$sql_ship = "UPDATE customer_order SET $update_string WHERE order_number='{$_SESSION['order_number']}'";
	$cxn = connect_to_db("vars.inc");
	$result = mysqli_query($cxn,$sql_ship) or die("sql_ship: ".mysqli_error($cxn));
	extract($_POST);
	include("fields_summary.inc");
	include("summary_page.inc");
}
elseif(isset($_POST['Ship']))
{
	include("fields_ship_info.inc");
	include("single_form.inc");
}
elseif(isset($_POST['Final']))
{
	switch($_POST['Final'])
	{
		case "Continue Shopping":
			header("Location: shocatalog.php");
			break;
		case "Cancel Order":
			#include("fields_cancel.inc");
			#include("cancel_message.inc");
			unset($_SESSION['order_number']);
			session_destroy();
			exit();
			break;
		case "Submit Order":
			$cxn = connect_to_db("vars.inc");
			$sql = "UPDATE customer_order SET submitted='yes'
						WHERE order_number='{$_SESSION['order_number']}'";
			$result = mysqli_query($cxn,$sql) or die("Error: ".mysqli_error($cxn));
			#processCCInfo();
			#sendOrder();
			#include("fields_accept.inc");
			#include("accept_message.inc");
			#email();
			session_destroy();
			break;
	}
}
?>