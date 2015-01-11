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
header("Content-type: text/xml; charset=UTF-8") ;

$lat=$_GET['Lat'];
$lng=$_GET['Lng'];
$radius=$_GET['Radius'];

//$query="SELECT id, lat, lng, name FROM locations WHERE lat >=".$_GET['LatMin']." and lat <= ".$_GET['LatMax']." and lng >= ".$_GET['LngMin']." and lng <= ".$_GET['LngMax']." LIMIT 0 , 100";
//$query="SELECT id, lat, lng, full_name FROM geocode_md_unicode_1 WHERE lat >=".$_GET['LatMin']." and lat <= ".$_GET['LatMax']." and lng >= ".$_GET['LngMin']." and lng <= ".$_GET['LngMax']." LIMIT 0 , 100";
//$query="SELECT id, lat, lng, full_name FROM geocode_md_unicode LIMIT 0 , 10";
$query="SELECT id, lat, lng, full_name, (6371*acos(cos(radians($lat))*cos(radians(lat))*cos(radians(lng)-radians($lng))+sin(radians($lat))*sin(radians(lat)))) AS distance FROM geocode_md_unicode_2 where fc='P' HAVING distance < $radius ORDER BY distance LIMIT 0 , 100";

$query = mysql_query($query);
$out="<locations>";
while ($row=mysql_fetch_assoc($query))
{
//$out.="<location id=\"".$row["id"]."\" name=\"".$row["full_name"]."\" lat=\"".$row["lat"]."\" lng=\""'.$row["lng"]."\"/>";
$out.="<location id=\"".$row["id"]."\" name=\"".$row["full_name"]."\" lat=\"".$row["lat"]."\" lng=\"".$row["lng"]."\"/>";
}
$out.="</locations>";
echo $out;
?>
