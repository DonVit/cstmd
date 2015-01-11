<?
header('Content-type: text/xml;charset="utf-8"');

include('../../configs.php');
include('mysql.php');
$out="";
$out.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$out.="<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
$out.="xmlns:geo=\"http://www.google.com/geo/schemas/sitemap/1.0\">\n";
$out.="<url>\n";
$out.="<loc>".$newsdomain."/newskml.php</loc>\n";
$out.="<geo:geo>\n";
$out.="<geo:format>kml</geo:format>\n";
$out.="</geo:geo>\n";
$out.="</url>\n";
$out.="</urlset>\n";
echo $out;
?>