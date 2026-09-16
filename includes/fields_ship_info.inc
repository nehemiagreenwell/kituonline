<?php
/*
	File: fields_ship_info.inc
	Desc: Contains arrays with the field names and form
		  elements for the login web page.
*/
$page = array( "title" => "Food Shop Order: Shipping Information",
				"top" => "Food Shop Order: Shipping Information",
				"top2" => "Please fill in the information below",
				"bottom" => "Send questions and comments to admin@xFoodShop.com",
			);
$ship_info = array("email" => "Email Address",
					"ship_name" => "Name",
					"ship_street" => "Street",
					"ship_city" => "City",
					"ship_state" => "State",
					"ship_zip" => "Zip",
					"phone" => "Phone",
					"cc_type" => "Credit Card Type",
					"cc_number" => "Credit Card Number",
					"cc_exp" => "Expiration Date",
				  );
$cc_types = array("visa" => "Visa",
				   "mc" => "Master Card",
				   "amex" => "American Express",
				 );
$length = array( "email" => "55",
				  "ship_name" => "40",
				  "ship_street" => "55",
				  "ship_city" => "40",
				  "ship_zip" => "10",
				  "phone" => "15",
				  "cc_number" => "20",
			   );
$elements = array( "submit" => "Continue");
$months = array( 1 => "January", "February", "March", "April", "May",
						"June", "July", "August","September", "October",
						"November", "December"
			   );
?>