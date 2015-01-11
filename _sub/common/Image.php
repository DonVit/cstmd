<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Image extends DBManager {
	public $id;
	public $reftype;
	public $reftypeid;	
	public $imageurl;
	public $imagename;
	public $imagepath;
	public $imagenote;
	public $imagemain;
	function save(){
		//save files
        //move_uploaded_file($this->tmp_name, Config::$filespath."/".$this->new_name);
        Image::makeIcons_MergeCenter(Config::$filespath."/".$this->imagepath, Config::$filespath."/t".$this->imagepath, 100);

		DBManager::save();
	}
	public static function makeIcons_MergeCenter($src, $dst, $dsty){
		$fileExtension=strtolower(substr($src, strrpos($src, '.')+1));

		$size = getImageSize($src);
		$width = $size[0];
		$height = $size[1];
		//echo "$width=".$width;
		//echo "$height=".$height;
		if($height >= $dsty){
	
			$proportion_Y = $height / $dsty;
			$proportion_X = $proportion_Y;
	
			$dstx=$width/$proportion_X;
			$proportion = $proportion_X;
	
			$target['width'] = $dstx * $proportion;
			$target['height'] = $dsty * $proportion;
			//echo "fileExtension".$fileExtension;
			if($fileExtension == "jpg" OR $fileExtension=="jpeg"){
				$from = ImageCreateFromJpeg($src);
			}elseif ($fileExtension == "gif"){
				$from = ImageCreateFromGIF($src);
			}elseif ($fileExtension == "png"){
			 	$from = imageCreateFromPNG($src);
			}
				$new = ImageCreateTrueColor ($dstx,$dsty);
				//echo $new.",".$from.",".$dstx.",".$dsty.",".$target['width'].",".$target['height'];
				imagecopyresampled ($new,  $from,  0, 0, 0, 0, $dstx, $dsty, $target['width'], $target['height']);
			 	if($fileExtension == "jpg" OR $fileExtension == "jpeg"){
					imagejpeg($new, $dst, 100);
				}elseif ($fileExtension == "gif"){
					imagegif($new, $dst);
				}elseif ($fileExtension == "png"){
					imagepng($new, $dst);
				}
		}
	}
	
	public static function resize($maxFullSize, $maxThumbSize){
		list($oryginalWidth, $oryginalHeight) = getimagesize($this->tmp);
		if($oryginalWidth > $oryginalHeight){
			$fullWidth = $maxFullSize;
			$fullHeight = intval($maxFullSize*($oryginalHeight/$oryginalWidth));
			$thumbWidth = $maxThumbSize;
			$thumbHeight = intval($maxThumbSize*($oryginalHeight/$oryginalWidth));
		}
		else if($oryginalWidth < $oryginalHeight){
			$fullWidth = intval($maxFullSize*($oryginalWidth/$oryginalHeight));
			$fullHeight = $maxFullSize;
			$thumbWidth = intval($maxThumbSize*($oryginalWidth/$oryginalHeight));
			$thumbHeight = $maxThumbSize;
		}
		else{
			$fullWidth = $maxFullSize;
			$fullHeight = $maxFullSize;
			$thumbWidth = $maxThumbSize;
			$thumbHeight = $maxThumbSize;
		}
		$this->fullImage = imagecreatetruecolor($fullWidth,$fullHeight);
		$this->thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
		if($this->type == 'image/gif'){
			$sourceImage = imagecreatefromgif($this->tmp);
		}
		else if($this->type == 'image/jpeg'){
			$sourceImage = imagecreatefromjpeg($this->tmp);
		}
		else{
			$sourceImage = imagecreatefrompng($this->tmp);
		}
		imagecopyresampled($this->fullImage,$sourceImage,0,0,0,0,$fullWidth,$fullHeight,$oryginalWidth,$oryginalHeight);
		imagecopyresampled($this->thumbImage,$sourceImage,0,0,0,0,$thumbWidth,$thumbHeight,$oryginalWidth,$oryginalHeight);
	
	}
	public static function getErrorMsg($filename,$error){
		switch($error) {
			//The uploaded file exceeds the upload_max_filesize directive in php.ini.
			case 1 : $msg="Fisierul [$filename] e mai mare de 1MB.";
			break;
			//The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
			case 2 : $msg="Fisierul [$filename] e mai mare de 1MB.";
			break;
			//The uploaded file was only partially uploaded.
			case 3 : $msg="";
			break;
			//No file was uploaded.
			case 4 : $msg="";
			break;
			//Missing a temporary folder.
			case 6 : $msg="";
			break;
			//Failed to write file to disk
			case 7 : $msg="";
			break;
			//File upload stopped by extension
			case 8 : $msg="Extensia fisierului [$filename] nu este acceptata.";
			break;
		}
		return $msg;
	}
	public static function getImagesByRefType($reftype,$reftypeid){
		$i=new Image();
		$is=$i->getAll("reftype='".$reftype."' and reftypeid='".$reftypeid."'");
		return $is;
	}
	public static function getMainImageByRefType($reftype,$reftypeid){
		$i=new Image();
		$is=$i->getAll("reftype='".$reftype."' and reftypeid='".$reftypeid."'");
		if (count($is)!=0){
			foreach ($is as $i){
				if ($i->imagemain==1){
					return $i;
				}
			}
		}
		return null;
	}	
}

?>
