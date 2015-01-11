<?php
/*
 * Created on 24 Feb 2009
 *
 */
require_once('loader.php');
class ValidationImage extends WebPage{
	public function __construct() {
		parent::__construct("image/png");

		//get validation code	
		$validationcode=rand(1000,9999);

		//set validation code to session
		User::setValidationCode($validationcode);
		//Logger::setLogs(User::getCurrentUser());
		
		// Define .PNG image

		$imgWidth=60;
		
		$imgHeight=20;
		
		$gridStep=5;
		
		// Create image and define colors
		
		$image=imagecreate($imgWidth, $imgHeight);
		
		$colorWhite=imagecolorallocate($image, 255, 255, 255);
		
		$colorGrey=imagecolorallocate($image, 192, 192, 192);
		
		$colorBlue=imagecolorallocate($image, 0, 0, 255);
		
		// Create border around image
		
		imageline($image, 0, 0, 0, $imgHeight, $colorGrey);
		
		imageline($image, 0, 0, $imgWidth, 0, $colorGrey);
		
		imageline($image, $imgWidth-1, 0, $imgWidth-1, $imgHeight-1, $colorGrey);
		
		imageline($image, 0, $imgHeight-1, 249, $imgHeight-1, $colorGrey);
		
		// Create grid
		
		for ($i=1; $i<$imgWidth/$gridStep; $i++){
		
		imageline($image, $i*$gridStep, 0, $i*$gridStep, $imgHeight, $colorGrey);
		
		imageline($image, 0, $i*$gridStep, $imgWidth, $i*$gridStep, $colorGrey);
		
		}
		
		
		// Add text
		
		$textcolor = imagecolorallocate($image, 0, 0, 255);
		
		// write the string at the top left
		imagestring($image, 5, 10, 2, $validationcode, $textcolor);
		
		
		// Output graph and clear image from memory
		
		imagepng($image);
		
		imagedestroy($image);
	}
	function setView(){

	}	
}
$image=new ValidationImage();

?>
