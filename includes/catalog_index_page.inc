<?php
/*
*	File: catalog_index_page.inc
*   Desc: Displays the categories for the catalog.
*/
?>
<html>
<head><title><?php echo $page['title']; ?></title></head> 
<body>
<?php
 /* Display text before form */
 echo "<div style='margin-left: .1in'>
 <h1 align='center'> {$page['top']}</h1><hr>";
 /*Create form containing selection list*/
 echo "<form action='$_SERVER[PHP_SELF]' method='POST'>\n";
 foreach($food_categories as $key => $subarray)
 {
	echo "<h3>$key</h3>";
	echo "<ul>";
	foreach($subarray as $type)
	{
		echo "<input type='radio' name='interest' value='$type'><b>$type</b><br/>\n";
	}
	echo "</ul>";
 }
 echo "<p><input type='submit' name='Products' value='Submit'>\n
	   </form>\n";
?>
</div>
<hr><font size="-1"><?php echo "<center>" . $page['bottom'] ."</center>" ?>
</body></html>