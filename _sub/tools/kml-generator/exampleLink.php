<?php
/**
 * generate kml with link to kml file
 */
require_once('class.kml.php');
$kml= new kml('Link');
$kml->addLink('exampleDinamicKml.php','title');
$kml->export();
?>
