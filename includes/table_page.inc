<?php
/*
*	File:	table_page_oo.inc
*	Desc:	Defines an HTML page that displays items in a table of items
*			with prices. A column named quantity and a column named price are
*			multiplied together to get the item total price. The item totals are
*			summed.
*/
echo "<html><head><title>{$page['title']}</title></head>\named
		<body>\n";
echo "<h1 style='text-align: center'>{$page['top']}</h1>\n";
echo "<h2 style='text-align: center'>{$page['top2']}</h2>\n";
echo "<p style='font-weight: bold'>Order Number:<hr>\n";
echo "<table border='0' style='width: 100%'>\n";
echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
if(sizeof($this->items) > 0)
{
	echo "<tr>";
	foreach($table_headers as $header)
	{
		echo "<th>$header</th>\n";
	}
	echo "</tr>";
	echo "<tr><td colspan='6'><hr></td></tr>\n";
	for($i=1;$i<=sizeof($this->items);$i++)
	{
		echo "<tr>";
		echo "<td width='5%'>".($i+1)."</td>\n";
		$cat = new Catalog("localhost","root", "");
		$cat->selectCatalog("onlineorders");
		$cat_no = $this->items[$i]->getCatalogNumber();
		$food_name = $cat->getName($cat_no);
		echo "<td width='5%'>" . $cat_no . "</td>\n";
		echo "<td width='5%'>". $food_name ."</td>\n";
		echo "<td style='text-align: center; width: 20%'>
				<input type='text' name='item$cat_no' value='".$this->items[$i]->getQuantity()."'
						size='4'>lbs</td>\n";
		echo "<td style='text-align: right; width: 17%'>
			$".number_format($this->items[$i]->getPrice(),2)." per lb</td>\n";
		$total = $this->items[$i]->getQuantity() * $this->items[$i]->getPrice();
		echo "<td style='text-align: right; width: 7%'>$".number_format($total,2)."</td>\n";
		@$order_total = $order_total + $total;
	}
	$f_order_total = number_format($order_total,2);
	echo "<tr><td colspan='5'
			style='text-align: right; font-weight: bold'>Total</td>
			  <td style='text-align: right; line-height: 200%'>$$f_order_total</td></tr>\n";
	echo "<tr><td colspan='2' style='text-align: left'>
				<input type='submit' value='Continue Shopping'></td>\n";
	echo "		<td colspan='2' style='text-align: center'>
					<input type='submit' name='Ship' value='Submit Order'></td>\n";	
	echo "		<td colspan='2' style='text-align: right'>
					<input type='submit' name='Cart' value='Update Cart'></td>\n";	
	echo "</tr>\n";	
}
else	
{
	echo "<hr>";
	echo "<tr><td colspan='5' style='text-align: left'>
				<input type='submit' value='Continue Shopping'></td>\n";
}		
?>
</table></form></body></html>