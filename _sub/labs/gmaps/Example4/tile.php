<?php
     
           $x = $_GET["x"];
           $y = $_GET["y"];
           $zoom = $_GET["zoom"];


          $centerText = "x:" . $x ." y:" . $y . " zoom:" . $zoom; 


          // create the image and allocate some colours
          $im = ImageCreate(256,256);
          $grey = ImageColorAllocate($im,128,128,128);
          $black= ImageColorAllocate($im,0,0,0);
          $white=ImageColorAllocate($im,255,255,255);

          // put a white border on the tile so we can see where it ends.
          ImageRectangle($im,0,0,255,255,$white);

          // add in the center text
          ImageString($im,5,10,120,$centerText,$black);

          //Display lat lng     
          $rect = getLatLongXYZ($x,$y,$zoom); 

          // add in the bottom left and top right coordinates from the rectangle.
          ImageString($im,4,0,240,sprintf("%03.6f,%02.5f", $rect->x, $rect->y),$black);
          ImageString($im,4,90,0,sprintf("%03.6f,%02.5f", $rect->x + $rect->width, $rect->y + $rect->height),$black); 
     
          header("Content-type: image/png");
     
          ImagePNG($im); 

// utility class to hold the rectangle position and size.
class Rectangle {
    var $x,$y;
    var $width, $height;
} 

function getLatLongXYZ($x, $y, $zoom) {
$debug = $_GET['debug'];
      $lon      = -180; // x
      $lonWidth = 360; // width 360

      $lat       = -1;
      $latHeight = 2;

      $tilesAtThisZoom = 1 << ($zoom);
      $lonWidth  = 360.0 / $tilesAtThisZoom;
      $lon       = -180 + ($x * $lonWidth);
      $latHeight = 2.0 / $tilesAtThisZoom;
      $lat       = (($tilesAtThisZoom/2 - $y-1) * $latHeight);

if ($debug) {echo("(uniform) lat:$lat latHt:$latHeight<br>");}
      // convert lat and latHeight to degrees in a transverse mercator projection
      // note that in fact the coordinates go from about -85 to +85 not -90 to 90!
      $latHeight += $lat;
      $latHeight = (2 * atan(exp(PI() * $latHeight))) - (PI() / 2);
      $latHeight *= (180 / PI());

      $lat = (2 * atan(exp(PI() * $lat))) - (PI() / 2);
      $lat *= (180 / PI());


if ($debug) {echo("pre subtract lat: $lat latHeight $latHeight<br>");}
      $latHeight -= $lat;
if ($debug) {echo("lat: $lat latHeight $latHeight<br>");}

      if ($lonWidth < 0) {
         $lon      = $lon + $lonWidth;
         $lonWidth = -$lonWidth;
      }

      if ($latHeight < 0) {
         $lat       = $lat + $latHeight;
         $latHeight = -$latHeight;
      }


      $rect = new Rectangle();
      $rect->x = $lon;
      $rect->y = $lat;
      $rect->height = $latHeight;
      $rect->width= $lonWidth;

      return $rect;
   }

		   
?>
