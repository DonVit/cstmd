<?php
ini_set('max_execution_time', 500);
$in_path='D:\projects\selenium-web-driver\src\downloadfiles\download - sectii';
$out_path='D:\projects\cstmdlv\src\_sub\labs\imgcrop\output';

//1st crop area
$x11=558;
$y11=229;
$x12=671;
$y12=1264;
$width1=$x12-$x11;
$height1=$y12-$y11;
$to_crop_array_1 = array('x' =>$x11 , 'y' => $y11, 'width' => $width1, 'height'=> $height1);
//2nd crop area
$x21=529;
$y21=1305;
$x22=618;
$y22=1555;
$width2=$x22-$x21;
$height2=$y22-$y21;
$to_crop_array_2 = array('x' =>$x21 , 'y' => $y21, 'width' => $width2, 'height'=> $height2);


if ($handle = opendir($in_path)) {
    while (false !== ($entry = readdir($handle))) {
        if (!is_dir($entry)){
            $in_filename=$in_path.'\\'.$entry;
            $out_filename_1=$out_path.'\\'.str_replace('.','_a.',$entry);
            $out_filename_2=$out_path.'\\'.str_replace('.','_b.',$entry);
            crop_and_save($in_filename,$out_filename_1,$to_crop_array_1);
            crop_and_save($in_filename,$out_filename_2,$to_crop_array_2);
        }
    }
    closedir($handle);
}

function crop_and_save($infile,$outfile,$crop_array){
	$im = imagecreatefrompng($infile );
	$thumb_im = imagecrop($im, $crop_array);
	imagepng($thumb_im, $outfile, 0);
}

?>