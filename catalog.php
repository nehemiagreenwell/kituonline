<?php
/*
*	Program:	catalog_oo.php
*	Desc:		Displays a catalog of products. Displays two
*				different pages: an index page that shows
*				categories and a product page that is displayed
*				when the customer selects a category.
*/
require_once("classes/catalog.php");

if(isset($_POST['Products']))
{
	if(!isset($_POST['interest']))
	{
		header("location: catalog.php");
		exit();
	}
	else
	{
		try
		{
			$cat = new Catalog("localhost","root", "");
			$cat->selectCatalog("onlineorders");
			$cat->displayAllofType($_POST['interest'],2);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			exit();
		}
	}
}
else
{
	try
	{
		$foodcat = new Catalog("vars.inc");
		$foodcat->selectCatalog("onlineorders");
		$foodcat->displayCategories();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
}




















?>