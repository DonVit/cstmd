<?php
/**
 * generate kml file with point, line and polygon
 */
require_once('class.kml.php');
$kml= new kml('My house');
$kml->addPoint(-68.0470671863235,-38.96836236566559,0,'My house','My house in Neuquen Patagonia Argentina.');
$nRandom=rand(-70,70);

$kml->addLine(array(
	array('lon'=>-68.0470671863235,
		'lat'=>-38.96836236566559,
		'alt'=>0),
	array('lon'=>$nRandom,
		'lat'=>$nRandom,
		'alt'=>0)
		),'Line','Route imagin.');
$kml->addPolygon(array(
	array('lon'=>-68.04691170278939,
		'lat'=>-38.96816129642023,
		'alt'=>0),
	array('lon'=>-68.04653333331112,
		'lat'=>-38.96816211940057,
		'alt'=>0),
	array('lon'=>-68.04654058917755,
		'lat'=>-38.96802421712057,
		'alt'=>0),
	array('lon'=>-68.04691170278939,
		'lat'=>-38.96816129642023,
		'alt'=>0)
		),'Polygon','Imagin********.');
$kml->export();
?>
