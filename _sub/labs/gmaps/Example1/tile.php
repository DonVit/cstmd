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
     

     
          header("Content-type: image/png");
     
          ImagePNG($im); 
		   
?>