<?php

		
$out='<?xml version="1.0" encoding="utf-8"?>';
$out.='<kml xmlns="http://www.opengis.net/kml/2.2">';
$out.='<Placemark>';
$out.='<name>Simple placemark</name>';
$out.='<description>Desc</description>';
$out.='<Point>';
$out.='<coordinates>-122.0822035425683,37.42228990140251,10000</coordinates>';
$out.='</Point>';
$out.='<Placemark>';		
$out.='</kml>';

$out='';
$out.='<?xml version="1.0" encoding="UTF-8"?>';
$out.='<kml xmlns="http://www.opengis.net/kml/2.2">';
$out.='  <Placemark>';
$out.='    <name>Simple placemark</name>';
$out.='    <description>Attached to the ground. Intelligently places itself'; 
$out.='       at the height of the underlying terrain.</description>';
$out.='    <Point>';
$out.='      <coordinates>-122.0822035425683,37.42228990140251,0</coordinates>';
$out.='    </Point>';
$out.='  </Placemark>';
$out.='</kml>';


# set $myKMLCode together as a string
 $downloadfile="k.kml"; # give a name to appear at the client
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/vnd.google-earth.kml+xml kml; charset=utf8");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($out));
header("Pragma: no-cache");
header("Expires: 0");
echo $out;

?>
