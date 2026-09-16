<?php
/*
*	File:	summary_page.inc
*	Desc:	Defines an HTML page that displays a summary
*			of the order.
*/
echo "<html>
		<head><title>{$page['title']}</title></head>\n
		<body>\n";
echo "<h2 style='text-align: center'>{$page['top']}</h2>\n";
echo "<p style='position: absolute; margin-top: .25in;
				font-weight: bold'>Ship to:</p>";
echo "<p style='position: absolute; margin-top: .25in;
				margin-left: .75in'>$ship_name<br>";
echo "$ship_street<br>
	  $ship_city, $ship_state $ship_zip<br>
	  $phone<br>
	  $email<br>";
echo "<div style='margin-top: 1.5in'>";
echo "<p style='font-weight: bold'>
				<b>{$page['table']}</b> $table_name\n";
echo "<table border='0' style='width: 100%'>\n";
echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
echo "<tr>";
foreach($table_headers as $header)
{
	echo "<th>$header</th>\n";
}
echo "</tr>";
for($i=1;$i<=sizeof($items);$i++)
{
	echo "<tr>";
	echo "<td width='10%'>$i</td>";
	echo "<td width='10%'>{$items[$i]['catalog_number']}</td>";
	echo "<td>{$items[$i]['name']}</td>";
	echo "<td>{$items[$i]['quantity']}</td>";
	$f_price = number_format($items[$i]['price'],2);
	echo "<td style='text-align: right; width: 17%'>
				$$f_price per lb.</td>\n";
	$total = $items[$i]['quantity'] * $items[$i]['price'];
	$f_total = number_format($total,2);
	echo "<td style='text-align: right'>$$f_total</td>\n";
	echo "</tr>";
	@$order_subtotal = $order_subtotal + $total;
}
$f_order_subtotal = number_format($order_subtotal,2);
if(substr($ship_zip,0,5) > 75000 && substr($ship_zip,0,5) < 80000)
{
	$taxrate = .0700;
}
else
{
	$taxrate = 0.0;
}
$sales_tax = $order_subtotal * $taxrate;
$f_sales_tax = number_format($sales_tax,2);
$shipping = $shipping_rate * sizeof($items);
$f_shipping = number_format($shipping,2);
$order_total = $order_subtotal+$sales_tax+$shipping;
$f_order_total = number_format($order_total,2);
echo "<tr><td colspan='5' style='text-align: right;font-weight: bold'>
				Subtotal</td>
		  <td style='text-align: right; line-height: 200%'>
			$$f_order_subtotal</td></tr>\n";
echo "<tr><td colspan='5' style='text-align: right; font-weight: bold'>
				Sales Tax</td>
		  <td style='text-align: right; line-height: 50%'>
			$$f_sales_tax</td></tr>\n";
echo "<tr><td colspan='5' style='text-align: right; font-weight: bold'>
				Shipping</td>
		  <td style='text-align: right; line-height: 50%'>
			$$f_shipping</td></tr>\n";
echo "<tr><td colspan='5' style='text-align: right; font-weight: bold'>
				Total</td>
		  <td style='text-align: right; line-height: 300%'>
			$$f_order_total</td></tr>\n";
echo "<tr><td colspan='2' style='text-align: left'>
				<input type='submit' value='Continue Shopping'></td>\n";
echo "		<td colspan='1' style='text-align: center'>
					<input type='submit' name='Ship' 
						value='Edit Shopping Information'></td>\n";	
echo "		<td colspan='1' style='text-align: right'>
					<input type='submit' name='Final' 
						value='Cancel Order'></td>\n";	
echo "		<td colspan='2' style='text-align: right'>
					<input type='submit' name='Final' 
						value='Submit Order'></td>\n";	
echo "</tr></table></form></body></html>\n";
?>