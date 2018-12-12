<?php
class User extends DBManager{
	public $id;
	public $role_id;
	public $tipcontact_id;
	public $tipcompanie_id;
	public $name;
	public $url;
	public $phone;
	public $mobile;
	public $email;
	public $password;
	public $note;
	public $data;
	public $validation_code;
	public $validated;	
	
	public static function setCurrentUser($user){
		User::delCurrentProperty();
		User::delCurrentContact();
		User::delCurrentFiles();
		User::delCurrentCompany();
		$_SESSION["user"]=serialize($user);
	}
	public static function getCurrentUser(){
		if (isset($_SESSION["user"])){
			$user=unserialize($_SESSION["user"]);	
		} else {
			$user=new User();
			$user->id=0;
			$user->name="Anonymous";
			User::setCurrentUser($user);
		}
		return $user;
	}
	public static function isAuthenticated(){
		if (User::getCurrentUser()->id==0){
			return false;
		}else{
			return true;
		}
	}
	public static function setImobilCurrentScop($scop){
		$_SESSION["imobilscop"]=serialize($scop);
	}
	public static function getImobilCurrentScop(){
		if (!isset($_SESSION["imobilscop"])){
			User::setImobilCurrentScop(0);
		}
		return unserialize($_SESSION["imobilscop"]);;
	}	
	public static function setImobilCurrentTipImobil($tipimobil){
		$_SESSION["imobiltipimobil"]=serialize($tipimobil);
	}
	public static function getImobilCurrentTipImobil(){
		if (!isset($_SESSION["imobiltipimobil"])){
			User::setImobilCurrentTipImobil(0);
		}
		return unserialize($_SESSION["imobiltipimobil"]);
	}
	public static function setImobilCurrentSubTipImobil($subtipimobil){
		$_SESSION["imobilsubtipimobil"]=serialize($subtipimobil);
	}
	public static function getImobilCurrentSubTipImobil(){
		if (!isset($_SESSION["imobilsubtipimobil"])){
			User::setImobilCurrentSubTipImobil(0);
		}
		return unserialize($_SESSION["imobilsubtipimobil"]);
	}	
	public static function setImobilCurrentPage($page){
		$_SESSION["imobilpage"]=serialize($page);
	}
	public static function getImobilCurrentPage(){
		if (!isset($_SESSION["imobilpage"])){
			User::setImobilCurrentPage(0);
		}
		return unserialize($_SESSION["imobilpage"]);
	}
	public static function setImobilCurrentRaion($raion){
		$_SESSION["imobilraion"]=serialize($raion);
	}
	public static function getImobilCurrentRaion(){
		if (!isset($_SESSION["imobilraion"])){
			$raion=new Raion();
			$raion->id=0;
			$raion->name="Toate";
			User::setImobilCurrentRaion($raion);
		}
		return unserialize($_SESSION["imobilraion"]);
	}
	public static function setImobilCurrentLocation($location){
		$_SESSION["imobillocation"]=serialize($location);
	}
	public static function getImobilCurrentLocation(){
		if (!isset($_SESSION["imobillocation"])){
			$location=new Location();
			$location->id=0;
			$location->name="Toate";
			User::setImobilCurrentLocation($location);
		}
		return unserialize($_SESSION["imobillocation"]);
	}
	public static function setImobilCurrentSector($sector){
		$_SESSION["imobilsector"]=serialize($sector);
	}
	public static function getImobilCurrentSector(){
		if (!isset($_SESSION["imobilsector"])){
			$sector=new Sector();
			$sector->id=0;
			$sector->name="Toate";
			User::setImobilCurrentSector($sector);
		}
		return unserialize($_SESSION["imobilsector"]);
	}	
	public static function setCurrentProperty($property){
		$_SESSION["property"]=serialize($property);
	}
	public static function getCurrentProperty(){
		if (!isset($_SESSION["property"])){
			$property=new Property();
			$property->user_id=User::getCurrentUser()->id;
			$property->scop_id=1;
			$property->tipchirie_id=11;
			$r=Raion::getTopFirstRaion();
			$l=Location::getTopFirstLocationByRaionId($r->id);
			$s=Sector::getTopFirstSectorByLocalitateId($l->id);
			$property->raion_id=$r->id;
			$property->localitate_id=$l->id;
			$property->sector_id=$s->id;
			$property->centerlat=$r->lat;
			$property->centerlng=$r->lng;
			$property->zoom=$r->zoom;
			$property->maptype=2;
			User::setCurrentProperty($property);
		}
		return unserialize($_SESSION["property"]);
	}
	public static function delCurrentProperty(){
		if (isset($_SESSION["property"])){
			//session_unset("property");
			unset($_SESSION["property"]);
		}
	}
	public static function setCurrentContact($contact){
		$_SESSION["contact"]=serialize($contact);
	}
	public static function getCurrentContact(){
		if (!isset($_SESSION["contact"])){
			$contact=new Contact();
			if (User::isAuthenticated()){
				$contact->name=User::getCurrentUser()->name;
				$contact->url=User::getCurrentUser()->url;
				$contact->phone=User::getCurrentUser()->phone;
				$contact->mobile=User::getCurrentUser()->mobile;
				$contact->email=User::getCurrentUser()->email;
			}
			User::setCurrentContact($contact);
		}
		return unserialize($_SESSION["contact"]);
	}
	public static function delCurrentContact(){
		if (isset($_SESSION["contact"])){
			//session_unset("contact");
			unset($_SESSION["contact"]);
		}
	}
	public static function setChirieCurrentScop($scop){
		$_SESSION["chiriescop"]=serialize($scop);
	}
	public static function getChirieCurrentScop(){
		if (!isset($_SESSION["chiriescop"])){
			User::setChirieCurrentScop(0);
		}
		return $scop=unserialize($_SESSION["chiriescop"]);	;
	}	
	public static function setChirieCurrentTipImobil($tipimobil){
		$_SESSION["chirietipimobil"]=serialize($tipimobil);
	}
	public static function getChirieCurrentTipImobil(){
		if (!isset($_SESSION["chirietipimobil"])){
			User::setChirieCurrentTipImobil(0);
		}
		return unserialize($_SESSION["chirietipimobil"]);
	}
	public static function setChirieCurrentSubTipImobil($subtipimobil){
		$_SESSION["chiriesubtipimobil"]=serialize($subtipimobil);
	}
	public static function getChirieCurrentSubTipImobil(){
		if (!isset($_SESSION["chiriesubtipimobil"])){
			User::setChirieCurrentSubTipImobil(0);
		}
		return unserialize($_SESSION["chiriesubtipimobil"]);	
	}	
	public static function setChirieCurrentPage($page){
		$_SESSION["chiriepage"]=serialize($page);
	}
	public static function getChirieCurrentPage(){
		if (isset($_SESSION["chiriepage"])){
			User::setChirieCurrentPage(0);
		}
		return unserialize($_SESSION["chiriepage"]);
	}
	public static function setChirieCurrentRaion($raion){
		$_SESSION["chirieraion"]=serialize($raion);
	}
	public static function getChirieCurrentRaion(){
		if (!isset($_SESSION["chirieraion"])){
			$raion=new Raion();
			$raion->id=0;
			$raion->name="Toate";
			User::setChirieCurrentRaion($raion);
		}
		return unserialize($_SESSION["chirieraion"]);
	}
	public static function setChirieCurrentLocation($location){
		$_SESSION["chirielocation"]=serialize($location);
	}
	public static function getChirieCurrentLocation(){
		if (isset($_SESSION["chirielocation"])){
			$location=unserialize($_SESSION["chirielocation"]);	
		} else {
			$location=new Location();
			$location->id=0;
			$location->name="Toate";
			User::setChirieCurrentLocation($location);
		}
		return $location;
	}
	public static function setChirieCurrentSector($sector){
		$_SESSION["chiriesector"]=serialize($sector);
	}
	public static function getChirieCurrentSector(){
		if (isset($_SESSION["chiriesector"])){
			$sector=unserialize($_SESSION["chiriesector"]);	
		} else {
			$sector=new Sector();
			$sector->id=0;
			$sector->name="Toate";
			User::setChirieCurrentSector($sector);
		}
		return $sector;
	}
	public static function setChirieCurrentProperty($property){
		$_SESSION["chirieproperty"]=serialize($property);
	}
	public static function getChirieCurrentProperty(){
		if (isset($_SESSION["chirieproperty"])){
			$property=unserialize($_SESSION["chirieproperty"]);	
		} else {
			$property=new Property();
			$property->user_id=User::getCurrentUser()->id;
			$property->scop_id=2;
			$property->tipchirie_id=11;
			$r=Raion::getTopFirstRaion();
			$l=Location::getTopFirstLocationByRaionId($r->id);
			$s=Sector::getTopFirstSectorByLocalitateId($l->id);
			$property->raion_id=$r->id;
			$property->localitate_id=$l->id;
			$property->sector_id=$s->id;
			$property->centerlat=$r->lat;
			$property->centerlng=$r->lng;
			$property->zoom=$r->zoom;
			$property->maptype=3;
			User::setChirieCurrentProperty($property);
		}
		return $property;
	}
	public static function delChirieCurrentProperty(){
		if (isset($_SESSION["chirieproperty"])){
			//session_unregister("chirieproperty");
			unset($_SESSION["chirieproperty"]);
		}
	}
	public static function setChirieCurrentContact($contact){
		$_SESSION["chiriecontact"]=serialize($contact);
	}
	public static function getChirieCurrentContact(){
		if (isset($_SESSION["chiriecontact"])){
			$contact=unserialize($_SESSION["chiriecontact"]);	
		} else {
			$contact=new Contact();
			if (User::isAuthenticated()){
				$contact->name=User::getCurrentUser()->name;
				$contact->url=User::getCurrentUser()->url;
				$contact->phone=User::getCurrentUser()->phone;
				$contact->mobile=User::getCurrentUser()->mobile;
				$contact->email=User::getCurrentUser()->email;
			}
			User::setChirieCurrentContact($contact);
		}
		return $contact;
	}
	public static function delChirieCurrentContact(){
		if (isset($_SESSION["chiriecontact"])){
			//session_unregister("chiriecontact");
			unset($_SESSION["chiriecontact"]);
		}
	}		
	public static function setCurrentNews($news){
		$_SESSION["news"]=serialize($news);
	}
	public static function getCurrentNews(){
		if (isset($_SESSION["news"])){
			$news=unserialize($_SESSION["news"]);	
		} else {
			$news=new News();
			$news->centerlat="47.021929410040514";
			$news->centerlng="28.861770629882812";
			$news->zoom=11;
			$news->maptype=3;
			$news->lat="0";
			$news->lng="0";
			//$news->valid=1;
			$news->date=System::getCurentDateTime();
			User::setCurrentNews($news);
		}
		return $news;
	}
	public static function delCurrentNews(){
		if (isset($_SESSION["news"])){
			$news=unserialize($_SESSION["news"]);
			unset($_SESSION["news"]);
		}
	}	
	public static function setCurrentNewsLocations($newslocations){
		$_SESSION["newslocations"]=serialize($newslocations);
	}
	public static function getCurrentNewsLocations(){
		if (isset($_SESSION["newslocations"])){
			$newslocations=unserialize($_SESSION["newslocations"]);	
		} else {
			$newslocations=array();
			User::setCurrentNewsLocations($newslocations);
		}
		return $newslocations;
	}
	public static function delCurrentNewsLocations(){
		if (isset($_SESSION["newslocations"])){
			$newslocations=unserialize($_SESSION["newslocations"]);
			unset($_SESSION["newslocations"]);
		}
	}	
	public static function setCurrentPhoto($photo){
		$_SESSION["photo"]=serialize($photo);
	}
	public static function getCurrentPhoto(){
		if (isset($_SESSION["photo"])){
			$photo=unserialize($_SESSION["photo"]);	
		} else {
			$photo=new Photo();
			$photo->setRaion(Raion::getTopFirstRaion());
			$photo->localitate_id=Location::getTopFirstLocationByRaionId($photo->raion_id)->id;
			$photo->user_id=User::getCurrentUser()->id;
			$photo->data=System::getCurentDateTime();
			User::setCurrentPhoto($photo);
		}
		return $photo;
	}
	public static function delCurrentPhoto(){
		if (isset($_SESSION["photo"])){
			$photo=unserialize($_SESSION["photo"]);
			unset($_SESSION["photo"]);
		}
	}	
	public static function setCurrentAlbum($album){
		$_SESSION["album"]=serialize($album);
	}	
	public static function getCurrentAlbum(){
		if (isset($_SESSION["album"])){
			$album=unserialize($_SESSION["album"]);
		} else {
			$album=new Album();
			$album->setRaion(Raion::getTopFirstRaion());
			$album->localitate_id=Location::getTopFirstLocationByRaionId($album->raion_id)->id;
			$album->user_id=User::getCurrentUser()->id;
			$album->data=System::getCurentDateTime();
			User::setCurrentAlbum($album);
		}
		return $album;
	}	
	public static function delCurrentAlbum(){
		if (isset($_SESSION["album"])){
			$album=unserialize($_SESSION["album"]);			
			unset($_SESSION["album"]);
		}
	}
	public static function setCurrentAlbumFiles($albumfiles){
		$_SESSION["albumfiles"]=serialize($albumfiles);
	}
	public static function getCurrentAlbumFiles(){
		if (isset($_SESSION["albumfiles"])){
			$albumfiles=unserialize($_SESSION["albumfiles"]);
		} else {
			$albumfiles=array();
			User::setCurrentAlbumFiles($albumfiles);
		}
		return $albumfiles;
	}
	public static function delCurrentAlbumFiles(){
		if (isset($_SESSION["albumfiles"])){
			$albumfiles=unserialize($_SESSION["albumfiles"]);
			unset($_SESSION["albumfiles"]);
		}
	}	
	public static function setCurrentCompany($company){
		$_SESSION["company"]=serialize($company);
	}
	public static function getCurrentCompany(){
		if (isset($_SESSION["company"])){
			$company=unserialize($_SESSION["company"]);	
		} else {
			$company=new Company();
			$company->user_id=User::getCurrentUser()->id;
			$company->setRaion(Raion::getTopFirstRaion());
			$company->localitate_id=Location::getTopFirstLocationByRaionId($company->raion_id)->id;
			$company->created_by=User::getCurrentUser()->id;
			$company->created_date=System::getCurentDateTime();
			$company->valid=1;
			User::setCurrentCompany($company);
		}
		return $company;
	}
	public static function delCurrentCompany(){
		if (isset($_SESSION["company"])){
			$company=unserialize($_SESSION["company"]);
			unset($_SESSION["company"]);
		}
	}		
	public static function setCurrentFiles($files){
		$_SESSION["files"]=serialize($files);
	}
	public static function getCurrentFiles(){
		if (isset($_SESSION["files"])){
			$files=unserialize($_SESSION["files"]);	
		} else {
			$files=array();
			for ($i = 0; $i <= 4; $i++) {
				$f=new Image();
				if ($i==0){$f->imagemain=1;}
				$files[]=$f;
			}
		}
		return $files;
	}
	public static function delCurrentFiles(){
		if (isset($_SESSION["files"])){
			unset($_SESSION["files"]);
		}
	}
	public static function setCurrentMap($map){
		$_SESSION["map"]=serialize($map);
	}
	public static function getCurrentMap(){
		if (isset($_SESSION["map"])){
			$map=unserialize($_SESSION["map"]);	
		} else {
			$map=new Map();
			$map->lat="0";
			$map->lng="0";
			$map->centerlat="47.02824837080608";
			$map->centerlng="28.839454650878906";		
			$map->zoom=7;
			$map->maptype=0;
			$map->data=System::getCurentDateTime();				
			User::setCurrentMap($map);
		}
		return $map;
	}
	public static function delCurrentMap(){
		if (isset($_SESSION["map"])){
			$map=unserialize($_SESSION["map"]);
			unset($_SESSION["map"]);
		}
	}		
		
	public static function setValidationCode($validationcode){
		$_SESSION["validationcode"]=$validationcode;
	}
	public static function getValidationCode(){
		$validationcode=$_SESSION["validationcode"];
		return $validationcode;
	}
	public static function Login($email,$password){
		$o=DBManager::doSql("select * from user where email='".$email."' and password='".$password."'");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}
	}
}
?>
