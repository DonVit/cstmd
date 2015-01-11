<?php
require("../mysqlconnect.php");
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//XML Header
header("content-type:text/xml");

$query="SELECT id, lat, lng, name FROM locations WHERE lat >=".$_GET['LatMin']." and lat <= ".$_GET['LatMax']." and lng >= ".$_GET['LngMin']." and lng <= ".$_GET['LngMax'];

$query = mysql_query($query);
echo "<locations>";
while ($row=mysql_fetch_assoc($query))
{
	echo '<location id="'.$row['id'].'" name="'.$row['name'].'" lat="'.$row['lat'].'" lon="'.$row['lng'].'"/>';
}
echo "</locations>";
?>
