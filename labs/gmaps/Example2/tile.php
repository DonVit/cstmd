<?php
           define("NO_DATA", "../no_data.gif");
           define("ZOOM_IN", "../zoom_in.gif");
           define("ZOOM_OUT", "../zoom_out.gif");
     
           $x = $_GET["x"];
           $y = $_GET["y"];
           $z = $_GET["zoom"];
     
           $filename = "../maptiles/${x}_${y}_${z}.gif";
		       
           if ( $z < 1 ) {
                 $content = file_get_contents( ZOOM_OUT );
           }else if ( $z > 11 ){
                 $content = file_get_contents( ZOOM_IN );
           }else if (is_numeric($x) && is_numeric($y) && is_numeric($z) && file_exists($filename)){
                 $content = file_get_contents( $filename );
           }else{
                 $content = file_get_contents( NO_DATA );
           }
     
           header("Content-type: image/gif");
     
           echo $content;
		   
?>
