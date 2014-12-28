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
	function getLongTitle(){
		$r=new Raion();
		$r->loadById($this->raion_id);
		$l=new Location();
		$l->loadById($this->localitate_id);
		//$t=$r->getFullNameDescription().', '.$l->getFullNameDescription().', '.$this->title.' (din data: '.date("Y-m-d", strtotime($this->data)).')';
		$t=$r->getFullNameDescription().', '.$l->getFullNameDescription().', '.$this->title;
		return $t;	
	}
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
	function getPhotosInRadius($n=10){
		$sql="SELECT photos.*, (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat)))) AS distance FROM photos ORDER BY distance LIMIT 0 , ".$n;
		$ps=$this->doSql($sql);
		return $ps;
	}
	function getLatestPhotos($n=10){
		$sql="SELECT photos.* FROM photos ORDER BY data DESC LIMIT 0, ".$n;
		$ps=$this->doSql($sql);
		return $ps;
	}	
	function getPhotosByLatLng($lat,$lng,$n=8){
		$sql="SELECT photos.*, (6371*acos(cos(radians($lat))*cos(radians(lat))*cos(radians(lng)-radians($lng))+sin(radians($lat))*sin(radians(lat)))) AS distance FROM photos where (6371*acos(cos(radians($lat))*cos(radians(lat))*cos(radians(lng)-radians($lng))+sin(radians($lat))*sin(radians(lat))))<=10 ORDER BY distance LIMIT 0 , ".$n;
		$ps=$this->doSql($sql);
		return $this->getPhotosTable($ps);
	}
	function getPhotosTable($ps){
		$out="";
		//$ps=$n->getNewsByCategory();
		if (count($ps)!=0){
			$out.='<table style="font-size:85%;width:100%">';
			$i=0;
			foreach($ps as $p){
				if ($i==0){
					$out.='<tr>';
				}
				if ($i==4){
					$out.='</tr><tr>';
				}
				//$out.="<td class=\"newsgroup_td\"><div><a href=\"".$this->getBaseName()."?id=$p->id\"><img src=\"".Config::$imagessite."/thumbs/".$p->image_file."\" alt=\"".$p->image_description."\" class=\"newsgroup_img\"></img><p class=\"newsgroup_p\">$p->title</p></a></div></td>";
				$out.='<td style="width:25%"><div><a href="'.$this->getUrl(Config::$imagessite."/index.php","action=viewimage").'&id='.$p->id.'" style="text-align: left;text-decoration: none;"><img src="'.Config::$imagessite.'/files/s'.$p->file.'" class="imageborder"  style="width:120px;"></img><p class="newsgroup_p">'.$p->title.'</p></a></div></td>';
				if ($i==8){
					$out.='</tr>';
				}
				$i=$i+1;
			}
			$out.='</table>';
			//$out.="</div>";
		}
	
		return $out;
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
