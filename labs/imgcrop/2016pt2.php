<?php
ini_set('max_execution_time', 1000);
$in_path='E:\projects\grabber\grabber\prezenta\t2\imgs';
$out_path='E:\projects\grabber\grabber\prezenta\t2\imgs\out';

$rows=1;
$rowhight=1800;
//1st crop area
$x11=400;
$y11=110;
$x12=450;
$y12=$y11+$rowhight*$rows;
$width1=$x12-$x11;
$height1=$y12-$y11;
$to_crop_array_1 = array('x' =>$x11 , 'y' => $y11, 'width' => $width1, 'height'=> $height1);
//2nd crop area
$x21=1200;
$y21=311;
$x22=1440;
$y22=$y21+56*$rows;;
$width2=$x22-$x21;
$height2=$y22-$y21;
$to_crop_array_2 = array('x' =>$x21 , 'y' => $y21, 'width' => $width2, 'height'=> $height2);


if ($handle = opendir($in_path)) {
    while (false !== ($entry = readdir($handle))) {
        if (!is_dir($entry)){
            $in_filename=$in_path.'\\'.$entry;
            //$f1=str_replace('.','_a.',$entry);
            //$f2=str_replace('.','_b.',$entry);
            $out_filename_1=$out_path.'\\'.$entry;
            //$out_filename_2=$out_path.'\\'.$f2;
            crop_and_save($in_filename,$out_filename_1,$to_crop_array_1);
            //crop_and_save($in_filename,$out_filename_2,$to_crop_array_2);
            //clear_image($out_filename_1,$rows,$rowhight);
            /*
			$orig_im = imagecreatefrompng($in_filename);
			$croped_im_1 = imagecrop($orig_im, $to_crop_array_1);
			$croped_im_2 = imagecrop($orig_im, $to_crop_array_2);
			$merged_im=imagecreate($width1,($height1+$height2));
			imagecopymerge($merged_im,$croped_im_1,0,0,0,0,$width1,$height1,100);
			imagecopymerge($merged_im,$croped_im_2,0,$height1,0,0,$width2,$height2,100);
			imagepng($merged_im, $out_filename, 0);  
			*/       
        }
    }
    closedir($handle);
    echo '<img src="output/2016t1/'.$f1.'" ><p>';
    echo '<img src="output/2016t1/'.$f2.'" ><p>';
}

function crop_and_save($infile,$outfile,$crop_array){
	$im = imagecreatefrompng($infile );
	$thumb_im = imagecrop($im, $crop_array);
	imagepng($thumb_im, $outfile, 0);
}
function clear_image($infile,$rows,$rowhight){
	$x=0;
	$y=30;
	$h=20;
	$img = imagecreatefrompng($infile); 
	
	// Set a colour for the sides of the rectangle
	$color = imagecolorallocate($img, 159, 232, 251); 
	for ($i = 0; $i <= $rows-1; $i++) {
		$s=$y+($i*$rowhight);
		$e=$s+$h;
		imagefilledrectangle($img, $x, $s, $x+400, $e, $color);
	}
		// Save the image
	imagepng($img, $infile); // or imagejpeg(), etc.
}

?>