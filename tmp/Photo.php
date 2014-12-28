<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Photo extends DBManager {
	public $id;
	public $title;
	public $note;	
	public $file;
	public $raion_id;
	public $localitate_id;
	public $centerlat;
	public $centerlng;
	public $lat;
	public $lng;
	public $zoom;
	public $maptype;						
	public $data;
	public $contor;
	function getPrevPhotoId(){
		$sql="SELECT id FROM `photos` WHERE id < $this->id order by id desc limit 0,1";
		$ps=$this->doSql($sql);
		$out=0;
		if (count($ps)!=0){
			foreach($ps as $p){
				$out=$p->id;
			}
		}
		return $out;
	}
	function getNextPhotoId(){
		$sql="SELECT id FROM `photos` WHERE id > $this->id limit 0,1";
		$ps=$this->doSql($sql);
		$out=0;
		if (count($ps)!=0){
			foreach($ps as $p){
				$out=$p->id;
			}
		}
		return $out;
	}
	function setRaion($raion){
		$this->raion_id=$raion->id;
		$this->centerlat=$raion->lat;
		$this->centerlng=$raion->lng;	
		$this->lat=0;
		$this->lng=0;	
		$this->zoom=$raion->zoom;
		$this->maptype=3;
	}
	function getTableName(){
		return "photos";
	}	
	function save(){
		//save files
        //move_uploaded_file($this->tmp_name, Config::$filespath."/".$this->new_name);
        //Image::makeIcons_MergeCenter(Config::$filespath."/".$this->filepath, Config::$filespath."/t".$this->filepath, 100);
		//Photo::makeIcons_MergeCenter("files/".$this->file, "files/t".$this->file, 100);
		//Photo::makeIcons_MergeCenter("files/".$this->file, "files/s".$this->file, 300);
        //unlink("files/".$this->file);

		DBManager::save();
	}
	public static function MakeThumb($src, $dst){
		$rtn=false;
		$fileExtension=strtolower(substr($src, strrpos($src, '.')+1));
		$size = getImageSize($src);
		$width = $size[0];
		$height = $size[1];
		$newwidth = 175;
		$newheight = 125;
	
		if($fileExtension == "jpg" OR $fileExtension=="jpeg"){
			$from = ImageCreateFromJpeg($src);
		}elseif ($fileExtension == "gif"){
			$from = ImageCreateFromGIF($src);
		}elseif ($fileExtension == "png"){
		 	$from = imageCreateFromPNG($src);
		}
			$new = ImageCreateTrueColor ($newwidth,$newheight);
			$rtn=imagecopyresampled ($new,  $from,  0, 0, 0, 0, $newwidth,$newheight, $width, $height);
		 	if($fileExtension == "jpg" OR $fileExtension == "jpeg"){
				$rtn=imagejpeg($new, $dst, 100);
			}elseif ($fileExtension == "gif"){
				$rtn=imagegif($new, $dst);
			}elseif ($fileExtension == "png"){
				$rtn=imagepng($new, $dst);
		}
		unlink($src);// delete original image
		return $rtn;
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
}

?>
