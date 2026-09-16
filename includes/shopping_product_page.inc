<?php
/*
*	File: shopping_product_page.inc
*	Desc: Displays the products in the catalog for the
*		  selected category.
*/
?>
<html>
<head><title><?php echo $page['title']; ?></title></head>
<body>
<?php
echo "<form action='shoppingcart.php' method='POST'>\n
		<p style='text-align: right'>\n
		<input type='submit' name='Cart' value='View Shopping Cart'>\n
	 </form>\n";
echo "<div style='margin-left: .1in; margin-right: .1in'>
	  <h1 align='center'>{$page['top']}</h1>
	  <p><font size='+2'><b>{$_POST['interest']}</b></font>";
echo "<p align='right'>($n_products products found)</p>";
echo "<table border='0' cellpadding = '3' width= '100%'>";
echo "<tr>";
foreach($table_heads as $heading)
{
	echo "<th>$heading</th>";
}
echo "</tr>";
echo "<form action='shopcatalog.php' method='POST'>\n";
for($i=$n_start;$i<=$n_end;$i++)
{
	echo "<tr>";
	echo "<td style='text-align: center'>{$all[$i]->catalog_number}</td>";
	echo "<td style='text-align: center'>{$all[$i]->name}</td>";
	echo "<td style='text-align: center'>{$all[$i]->description}</td>";
	echo "<td style='text-align: center'>
				\${$all[$i]->price}/lb</td>";
	echo "<td style='text-align: center'>
			<img src='images/{$all[$i]->pix}' width='55' height='60'></td>";
	echo "<td style='text-align: center'><input type='text' name='item{$all[$i]->catalog_number}'
				value='0' size='4'></td>\n";
	echo "</tr>";
}
echo "<input type='hidden' name='n_end' value='$n_end'>";
echo "<input type='hidden' name='interest' value='$_POST[interest]'>";
echo "<tr>
		<td colspan='2'>
			<input type='submit' value='Select another category'></td>\n";
echo	"<td colspan='2' style='text-align='center'><input type='submit' name='Cart'
			value='Add Items to Cart'>\n";
echo 	"<td colspan='2' style='text-align: right'>\n";
		if($n_end > $n_per_page)
		{
			echo "<input type='submit' name='Products' value='Previous'>";
		}
		if($n_end < $n_products)
		{
			echo "<input type='submit' name='Products' value='Next'>";
		}
echo "</td></form></tr></table>";
echo "<p style='text-align: center; font-size: 75%'>{$page['bottom']}";
?>
</div></body></html>

