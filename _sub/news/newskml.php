<?php
header("Content-Type: application/vnd.google-earth.kml+xml; charset=utf-8");

include('hdr.php');

$out="";
$out.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$out.="<kml xmlns=\"http://earth.google.com/kml/2.2\">\n";
$out.="<Document>\n";
$out.="<name>Ştiri \"Imobil Moldova\"</name>\n";
$out.="<Style id=\"news-balloon-style\">\n";
$out.="<BalloonStyle>\n";
$out.="<text>\n";
$out.="<![CDATA[\n";
$out.="<b>$[name]</b>\n";
$out.="<p>\n";
$out.="<img src=\"$[image_file]\" border=\"1\" hspace=\"5\" alt=\"$[image_description]\"><br>\n";
$out.="</p>\n";
$out.="<p>\n";
$out.="$[description]\n";
$out.="</p>\n";
$out.="<p>\n";
$out.="<a href=\"$[link]\">mai mult</a>\n";
$out.="</p>\n";
$out.="]]>\n";
$out.="</text>\n";
$out.="</BalloonStyle>\n";
$out.="</Style>\n";

$sql = "select id, url,image_file,image_url,image_description,map_lat, map_lng, map_title, map_description  from news where (map_lat != \"\") and (map_lng  != \"\") LIMIT 1,1";
//echo $sql;
$result = mysql_query($sql);

while($row = mysql_fetch_object($result)){
$out.="<Placemark>\n";
$out.="<name>$row->map_title</name>\n";
$out.="<styleUrl>#news-balloon-style</styleUrl>\n";
$out.="<description>$row->map_description</description>\n";
$out.="<ExtendedData>\n";
$out.="<Data name=\"link\">\n";
$out.="<value>$newsdomain/index.php?id=$row->id</value>\n";
$out.="</Data>\n";
$out.="<Data name=\"image_file\">\n";
$out.="<value>$newsdomain/images/$row->image_file</value>\n";
$out.="</Data>\n";
$out.="<Data name=\"image_url\">\n";
$out.="<value>$row->image_url</value>\n";
$out.="</Data>\n";
$out.="<Data name=\"image_description\">\n";
$out.="<value>$row->image_description</value>\n";
$out.="</Data>\n";
$out.="</ExtendedData>\n";

$out.="<Point>\n";
$out.="<coordinates>$row->map_lng,$row->map_lat</coordinates>\n";
$out.="</Point>\n";
$out.="</Placemark>\n";
}
$out.="</Document>\n";
$out.="</kml>\n";
echo $out;
?>
