<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Company extends DBManager {
	public $id;
	public $user_id;
	public $company_type_id;
	public $name;
	public $text;
	public $description;
	public $keywords;			
	//public $logo_file;
	//public $logo_description;
	public $raion_id;
	public $localitate_id;
	public $sector_id;	
	public $strada;
	public $casa_nr;
	public $scara_nr;
	public $apartament_nr;
	public $noteadresa;	
	
	public $contactname;
	public $phone;
	public $phone2;
	public $mobile;
	public $fax;
	public $contacturl;
	public $rssfeed;
	public $email;	
	public $skypeid;
	public $notecontact;
							
	public $centerlat;
	public $centerlng;
	public $lat;
	public $lng;
	public $zoom;
	public $maptype;						
	public $contor;
	public $valid;
	public $created_by;
	public $created_date;
	public $modified_by;
	public $modified_date;					

	function getTableName(){
		return "company";
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
	function setRaion($raion){
		$this->raion_id=$raion->id;
		$this->centerlat=$raion->lat;
		$this->centerlng=$raion->lng;	
		$this->lat=0;
		$this->lng=0;	
		$this->zoom=$raion->zoom;
		$this->maptype=3;
	}
	function getCompanyType(){
		$ct=new CompanyType();
		$ct->loadById($this->company_type_id);
		return $ct;
	}
	function getRaion(){
		$raion=new Raion();
		$raion->loadById($this->raion_id);
		return $raion;
	}
	function getLocation(){
		$localitate=new Location();
		$localitate->loadById($this->localitate_id);
		return $localitate;
	}			
	function getSector(){
		$sector=new Sector();
		$sector->loadById($this->sector_id);
		return $sector;
	}
	function getAdresa(){
		return $this->getLocation()->getFullName().', '.$this->strada;
	}	
	function getNews1(){
		$n=new News();
		$ns=$n->getAll("company_id=".$this->id,"`date` desc",0,8);
		return $ns;		
	}
	function getData(){
		//return System::getDate(date_parse($this->data));
		return substr($this->created_date,0,10);
	}					
}

?>
