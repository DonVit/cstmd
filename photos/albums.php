<?php

require_once(__DIR__ . '/../main/loader.php');
 
class AlbumsWebPage extends MainWebPage {
	public $step=1;
	public $steps=1;
	public $steptitle="";
	public $currentalbum;	
	public $errormessage="";
	
	function __construct(){
		parent::__construct();
		
			//check if user is login
		if (!User::isAuthenticated()){
			$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		}
				
		$this->setJavascript("js/scripts.js");
		
		$this->setTitle('Adauga Album');
		$this->setLogoTitle('Adauga Album');
		
		$this->currentalbum=User::getCurrentAlbum();
		$this->currentalbumfiles=User::getCurrentAlbumFiles();		
		
		if (isset($this->id)){
			
			if (User::getCurrentUser()->id==0){
					$this->redirect($this->getUrl('index.php','id='.$this->id));
			}

			$album=new Album();
			$album->loadById($this->id);
			
			if (($album->user_id!=User::getCurrentUser()->id)){
				if (User::getCurrentUser()->role_id!=2){
					$this->redirect($this->getUrl('index.php','id='.$this->id));
				}
			}
			
			$albumfiles=$album->getPhotos();
				
			User::setCurrentAlbum($album);
			User::setCurrentAlbumFiles($albumfiles);
			
		} else {		
			if (isset($this->currentalbum->id)){
				User::delCurrentAlbum();
				User::delCurrentAlbumFiles();
			}
		}	
		
		$this->currentalbum=User::getCurrentAlbum();
		$this->currentalbumfiles=User::getCurrentAlbumFiles();
		
		if (isset($_POST)){
		
			foreach ($_POST as $field => $value) {
				if ($this->currentalbum->isMember($field)){
					
					//to change ceterlat and centerlng when location changed
					if (($field=='raion_id')&&($this->currentalbum->raion_id!=$value)){
						$r=new Raion();
						$r->loadById($value);
						$this->currentalbum->setRaion($r);			
					}
					$this->currentalbum->$field=$this->getParamValue($value);
				}
			}
			
			if (count($_FILES)!=0){
				$count=0;				
				foreach ($_FILES['file']['name'] as $filename) {
					if ($_FILES['file']['error'][$count]==0){
						$f=new Photo();
						$newfilename=System::getRandomFileName($_FILES["file"]["name"][$count]);
						$f->file=$newfilename;
						move_uploaded_file($_FILES["file"]["tmp_name"][$count], "files/".$f->file);
						Photo::makeIcons_MergeCenter("files/".$f->file, "files/t".$f->file, 100);
						Photo::makeIcons_MergeCenter("files/".$f->file	, "files/s".$f->file, 300);
						$exifinfo=exif_read_data('files/'.$f->file, 0, true);
						//$this->currentalbum->exif=$this->getExifInfo($exifinfo);
						$f->data = Photo::get_datetimeoriginal($exifinfo);
						$latlng = Photo::get_location($exifinfo);
						$f->centerlat = $latlng['latitude'];
						$f->centerlng = $latlng['longitude'];
						$f->lat = $latlng['latitude'];
						$f->lng = $latlng['longitude'];
						$this->currentalbumfiles[$count]=$f;
					}
					$count=$count+1;					
				}
			}
			if (isset($_POST['photoid'])){
				$i=0;
				foreach ($_POST['photoid'] as $photoid) {
					$j=0;
					foreach ($this->currentalbumfiles as $file) {
						if ($file->id==$photoid){
							$this->currentalbumfiles[$j]->title=$_POST['phototitle'][$i];
							$this->currentalbumfiles[$j]->note=$_POST['photodescription'][$i];
							$this->currentalbumfiles[$j]->data=$_POST['photodata'][$i];
							break;
						}
						$j=$j+1;
					}
					$i=$i+1;
				}
			}			
		}
		User::setCurrentAlbum($this->currentalbum);
		User::setCurrentAlbumFiles($this->currentalbumfiles);
		$this->create();
	}
	function actionDefault(){
		$this->step=1;
		$this->steptitle="Adauga Foto Album";
		$this->setTitle($this->steptitle);
		if (isset($_POST["save"])){
					if (!isset($this->currentalbum->id)){
						$this->currentalbum->save();
					} else {
						$this->currentalbum->save();
					}
					foreach ($this->currentalbumfiles as $file) {
							$photo=new Photo();
							$photo->id=$file->id;
							$photo->album_id=$this->currentalbum->id;
							$photo->title=$file->title;
							$photo->note=$file->note;
							$photo->file=$file->file;
							$photo->user_id=$this->currentalbum->user_id;
							$photo->country_id=$this->currentalbum->country_id;
							$photo->raion_id=$this->currentalbum->raion_id;
							$photo->localitate_id=$this->currentalbum->localitate_id;

							if (empty($file->centerlat)) {
								$photo->centerlat=$this->currentalbum->centerlat;
								$photo->centerlng=$this->currentalbum->centerlng;
								$photo->lat=$this->currentalbum->lat;
								$photo->lng=$this->currentalbum->lng;
							} else {
								$photo->centerlat=$file->centerlat;
								$photo->centerlng=$file->centerlng;
								$photo->lat=$file->lat;
								$photo->lng=$file->lng;
							}

							$photo->zoom=$this->currentalbum->zoom;
							$photo->maptype=$this->currentalbum->maptype;
							$photo->data=$file->data;
							$photo->user_id=$this->currentalbum->user_id;
							$photo->save();
					}
					
					$redirectid=$this->currentalbum->id;
					$this->redirect($this->getUrl("albums.php","id=".$redirectid));
					User::delCurrentAlbum();
					User::delCurrentAlbumFiles();
		}
		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setAlbum());	
		$this->show();
	}
	function actionEdit(){
		$this->actionDefault();
	}
	
	function actionCancel(){
		if (!isset($this->id)){
			if (!empty($this->currentalbum->file)){
					unlink("files/".$this->currentalbum->file);
					unlink("files/t".$this->currentalbum->file);
					unlink("files/s".$this->currentalbum->file);
			}
		}
		User::delcurrentalbum();
		
		$this->redirect($this->getUrl("index.php"));
	}
	function actionDelete(){
		$this->step=1;
		$this->steps=1;
		$this->steptitle="Sterge Anunt";
		$this->setTitle($this->steptitle);
		if (isset($_POST)){
			if ($_POST["da"]){
				$this->currentalbum=User::getcurrentalbum();
				$this->currentalbum->delete();
				User::delcurrentalbum();
				$this->redirect($this->getUrl(Config::$accountssite."/index.php","action=Fotos"));
			}
			if ($_POST["nu"]){
				User::delcurrentalbum();	
				$this->redirect($this->getUrl(Config::$accountssite."/index.php","action=Fotos"));
			}
		}		
		$this->setCenterContainer($this->setDeleteAnunt());	
		$this->show();
	}
	function show($out=''){
		$out="";
		$out.='<div id="container">';		
		$out.='<div id="center" class="container center" style="width:1000px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
	function setAdress($out=''){
		$out.='<table style="height:100px;width:100%">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentalbum->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentalbum->raion_id,$this->currentalbum->localitate_id).'</td>';
		$out.='</tr>';
		$out.='</table>';
		return $this->getWizardPage($out);
	}		
	function setAlbum(){
		$out=' <table style="height:100px;width:100%">';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" value="'.$this->currentalbum->title.'" style="width:100%;"></td></tr>';
		$out.='<tr><td>Data:</td><td><input type="input" id="data" name="data" value="'.$this->currentalbum->data.'"></td></tr>';
		$out.='<tr><td>Descriere:</td><td><textarea id="description" name="description" style="width:100%;height:60px;">'.$this->currentalbum->description.'</textarea></td></tr>';		
		$out.='<tr><td>Tara:</td><td>'.Country::getCountryDropDown($this->currentalbum->country_id).'</td></tr>';
		$c=new Country();
		$c->loadById($this->currentalbum->country_id);
		$t=$c->ISO;
		if ($c->ISO=='MD') {
			$out.='<tr><td>Municipiul/Raionul:</td><td>'.Raion::getRaionDropDown($this->currentalbum->raion_id).'</td></tr>';
			$out.='<tr><td>Oras/Sat:</td><td>'.Location::getLocationDropDown($this->currentalbum->raion_id,$this->currentalbum->localitate_id).'</td></tr>';
		}
		$out.='<tr><td colspan="2">'.$this->setMap($this->currentalbum).'</td></tr>';
		foreach ($this->currentalbumfiles as $p){
			$out.='<tr><td><img src="files/t'.$p->file.'"></td><td><input type="hidden" id="photoid" name="photoid[]" value="'.$p->id.'"><input type="input" id="photodata" name="photodata[]" value="'.$p->data.'"><input type="input" id="phototitle" name="phototitle[]" value="'.$p->title.'" style="width:100%;"></br><textarea id="photodescription" name="photodescription[]" style="width:100%;height:60px;">'.$p->note.'</textarea></td></tr>';
		}
		$out.='<tr><td colspan="2"><input type="file" id="file" name="file[]" style="width:100%;" multiple></td></tr>';
		$out.='</table>';
		return $this->getWizardPage($out);
	}	
	function setValidation(){
		$out=' <table style="height:100px;width:100%">';
		$out.=' <tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';	
		return $this->getWizardPage($out);
	}	
	function setDeleteAnunt(){
		$out='';
		$out.='<table><tr>';
		$out.='<td><h2>Doresti sa stergi acest Anunt ?</h2></td>';    		
		$out.='</tr></table>';	
		return $this->getQuestionPage($out);	 
	}
}
$n=new AlbumsWebPage();
?>
