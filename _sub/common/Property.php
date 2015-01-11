<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Property extends DBManager {
	//imobil id
	public $id;
	//txn type
	public $user_id;
	public $contact_id;
	public $scop_id;
	public $tipimobil_id;

	// details Caracteristici generale
	public $subtipimobil_id;
	public $aria_totala;
	public $aria_locuibila;
	public $aria_lot;
	public $aria_masura_id;
	public $etaj;
	public $totaletaje;
	public $balcoane;
	public $pret;
	public $valuta_id;
	public $masura_id;
	public $negociabil;

	//de aduagat
	//public $perioadachirie;

	// details Caracteristici tehnice
	public $an;
	public $model_id;
	public $tipconstructie_id;
	public $stare_id;

	// Comunicatii
	public $electricitate;
	public $apeduct;
	public $canalizare;
	public $garaj;
	public $subsol;
	public $incalzire;
	public $telefon;
	public $gaz;
	public $bonitate;
	public $note;

	//Adresa
	public $raion_id;
	public $localitate_id;
	public $sector_id;
	public $strada;
	public $casa_nr;
	public $scara_nr;
	public $apartament_nr;
	public $noteadresa;

	//Map
	public $centerlat;
	public $centerlng;
	public $zoom;
	public $maptype;
	public $lat;
	public $lng;

	//Video url
	public $youtubeurl;
	
	//Others
	public $data;
	public $status;
	public $contor;
	function __construct(){
		$this->status=2; //draft
		$this->data=System::getCurentDateTime();	
	}
	function getTableName(){
		return "imobil";
	}
	function getContact(){
		if (!isset($this->contact)){
			$c=new Contact();
			$c->loadById($this->contact_id);
			$this->contact=$c;
		}
		return $this->contact;
	}
	function getImages(){
		if (!isset($this->images)){
			if (($this->scop_id==1)||($this->scop_id==3)){
				$i=new Image();
				$is=$i->getAll("reftype='i' and reftypeid=".$this->id);
				$this->images=$is;
			} else {
				$i=new Image();
				$is=$i->getAll("reftype='c' and reftypeid=".$this->id);
				$this->images=$is;
				
			}
		}
		return $this->images;
	}
	function getMainImage(){
		$is=$this->getImages();
		if (count($is)!=0){
			foreach ($is as $i){
				if ($i->imagemain==1){
					return $i;
				}
			}
		}
		return null;
	}				
	
	function getScop(){
		if (!isset($this->scop)){
			$s=new Scop();
			$s->loadById($this->scop_id);
			$this->scop=$s;
		}
		return $this->scop;
	}
	function getTipImobil(){
		if (!isset($this->tipimobil)){
			$t=new TipImobil();
			$t->loadById($this->tipimobil_id);
			$this->tipimobil=$t;
		}
		return $this->tipimobil;
	}
	function getSubTipImobil(){
		if (!isset($this->subtipimobil)){
			$st=new SubTipImobil();
			$st->loadById($this->subtipimobil_id);
			$this->subtipimobil=$st;
		}
		return $this->subtipimobil;
	}
	function getAriaTotala(){
		if ($this->aria_totala==0){
			return "";
		}else{
			return $this->aria_totala;	
		}
		
	}
	function getAriaLocuibila(){
		if ($this->aria_locuibila==0){
			return "";
		}else{
			return $this->aria_locuibila;	
		}
		
	}
	function getAriaLot(){
		if ($this->aria_lot!=0){
			return $this->aria_lot.' '.$this->getAriaMasura()->name;	
		}else{
			return "";
		}
		
	}
	function getBalcoane(){
		if ($this->balcoane==0){
			return "";
		}else{
			return $this->balcoane;	
		}
		
	}
	function getAriaMasura(){
		if (!isset($this->ariamasura)){
			$a=new AriaMasura();
			$a->loadById($this->aria_masura_id);
			$this->ariamasura=$a;
		}
		return $this->ariamasura;
	}
	function getEtaj(){
		$out="";
		if ($this->etaj!=0){
			$out.=$this->etaj;
		}
		if ($this->totaletaje!=0){
			$out.="/".$this->totaletaje;
		}
		return $out;
	}
	function getPret(){
			if ($this->pret!=0){
				return $this->pret.' '.$this->getValuta()->name.'/'.$this->getMasura()->name;	
			}
	}			
	function getModel(){
		if (!isset($this->model)){
			$m=new Model();
			$m->loadById($this->model_id);
			$this->model=$m;
		}
		return $this->model;
	}
	function getTipConstructie(){
		if (!isset($this->tipconstructie)){
			$tc=new TipConstructie();
			$tc->loadById($this->tipconstructie_id);
			$this->tipconstructie=$tc;
		}
		return $this->tipconstructie;
	}
	function getStare(){
		if (!isset($this->stare)){
			$s=new Stare();
			$s->loadById($this->stare_id);
			$this->stare=$s;
		}
		return $this->stare;
	}
	function getValuta(){
		if (!isset($this->valuta)){
			$v=new Valuta();
			$v->loadById($this->valuta_id);
			$this->valuta=$v;
		}
		return $this->valuta;
	}
	function getMasura(){
		if (!isset($this->masura)){
			$m=new Masura();
			$m->loadById($this->masura_id);
			$this->masura=$m;
		}
		return $this->masura;
	}
	function getRaion(){
		if (!isset($this->raion)){
			$r=new Raion();
			$r->loadById($this->raion_id);
			$this->raion=$r;
		} else {
			if ($this->raion->id!=$this->raion_id){
				$r=new Raion();
				$r->loadById($this->raion_id);
				$this->raion=$r;
			}			
		}
		return $this->raion;
	}
	function getLocation(){
		if (!isset($this->location)){
			$l=new Location();
			$l->loadById($this->localitate_id);
			$this->location=$l;
		} else {
			if ($this->location->id!=$this->localitate_id){
				$l=new Location();
				$l->loadById($this->localitate_id);
				$this->location=$l;
			}			
		}
		return $this->location;
	}
	function getSector(){
		if (!isset($this->sector)){
			$s=new Sector();
			$s->loadById($this->sector_id);
			$this->sector=$s;
		} else {
			if ($this->sector->id!=$this->sector_id){
				$s=new Sector();
				$s->loadById($this->sector_id);
				$this->sector=$s;
			}			
		}
		return $this->sector;
	}

	function getElectricitate(){
		if ($this->electricitate==1){
			return "Da";
		}
	}
	function getApeduct(){
		if ($this->apeduct==1){
			return "Da";
		}
	}
	function getCanalizare(){
		if ($this->canalizare==1){
			return "Da";
		}
	}
	function getGaraj(){
		if ($this->garaj==1){
			return "Da";
		}
	}
	function getSubsol(){
		if ($this->subsol==1){
			return "Da";
		}
	}
	function getIncalzire(){
		if ($this->incalzire==1){
			return "Da";
		}
	}
	function getGaz(){
		if ($this->gaz==1){
			return "Da";
		}
	}
	function getBonitate(){
		if ($this->bonitate!=0){
			return $this->bonitate;
		}
	}	
	function getTelefon(){
		if ($this->telefon==1){
			return "Da";
		}
	}
	function getNgociabil(){
		if ($this->negociabil==1){
			return "Da";
		}
	}
	function getAn(){
		if ($this->an!=0){
			return $this->an;
		}
	}
	function getData(){
		//return System::getDate(date_parse($this->data));
		return substr($this->data,0,10);
	}
	function getTitle(){
		
	}
	function getShortDescription(){
		$out="";
		$out.=$this->getScop()->name.': '.$this->getScop()->note.' ';
		$out.=$this->getTipImobil()->name.' - ';
		$out.=$this->getSubTipImobil()->name.' in ';
		if ($this->localitate_id!=0){
			$out.=$this->getLocation()->getFullNameDescription();
		} else {
			$out.=$this->getRaion()->getFullNameDescription();
		}
		//$out.=$this->getLocation()->getFullNameDescription().' din ';		
		//$out.=$this->getRaion()->getFullNameDescription();	
		if ($this->aria_totala!=0){
			$out.=", Suprafata ".$this->aria_totala." m2";
		}
		if ($this->aria_lot!=0){
			$out.=", Lotul ".$this->aria_lot;
			if ($this->aria_masura_id!=0){
				$out.=" ".$this->getAriaMasura()->name;
				
			}										
		}
		if ($this->pret!=0){
			$out.=", Pret ".$this->pret.' '.$this->getValuta()->name.'/'.$this->getMasura()->name;
		}									
		return $out;
	}
	function getLongDescription(){
		$out="";
		//$out="Detalii: ";
		$out.=$this->getScop()->name.', ';		
		$out.=$this->getScop()->note.' ';
		$out.=$this->getTipImobil()->name." - ";
		$out.=$this->getSubTipImobil()->name;
		if ($this->aria_totala!=0){
			$out.=", Suprafata totala ".$this->aria_totala." m2";
		}
		if ($this->aria_locuibila!=0){
			$out.=", Suprafata locuibila ".$this->aria_locuibila." m2";
		}
		if ($this->aria_lot!=0){
			$out.=", Aria lot ".$this->aria_lot;
			if ($this->aria_masura_id!=0){
				$out.=" ".$this->getAriaMasura()->name;
			}
		}
		if ($this->etaj!=0){
			$out.=", Etajul ".$this->etaj;
			if ($this->totaletaje!=0){
				$out.=" din ".$this->totaletaje;
			}
		}
		if ($this->balcoane!=0){
			$out.=", Balcoane ".$this->balcoane;
		}
		if ($this->pret!=0){
			$out.=", Pret ".$this->pret.' '.$this->getValuta()->name.'/'.$this->getMasura()->name;
		}
		if ($this->negociabil!=0){
			$out.=", Pret Negociabil ";
		}
		if ($this->negociabil!=0){
			$out.=", Pret Negociabil ";
		}
		if ($this->an!=0){
			$out.=", Anul constructiei ".$this->an;
		}
		if ($this->model_id!=0){
			$out.=", Model ".$this->getModel()->name;
		}
		if ($this->tipconstructie_id!=0){
			$out.=", Tip Constructie-".$this->getTipConstructie()->name;
		}																													
		if ($this->stare_id!=0){
			$out.=", Starea-".$this->getStare()->name;
		}																													
		if ($this->garaj!=0){
			$out.=", Garaj";
		}	
		if ($this->subsol!=0){
			$out.=", Subsol";
		}
		if ($this->incalzire!=0){
			$out.=", Incalzire Autonoma";
		}	
		if ($this->telefon!=0){
			$out.=", Telefon";
		}	
		if ($this->gaz!=0){
			$out.=", Gaz";
		}	
		//$out.="<br>";
		$out.=" Adresa: ";		
		$out.=$this->getRaion()->getFullNameDescription().", ";
		$out.=$this->getLocation()->getFullNameDescription();				
		if ($this->sector_id!=0){
			$out.=", ".$this->getSector()->name;
		}	
		if ($this->strada!=""){
			$out.=", ".$this->strada;
		}
		if ($this->casa_nr!=""){
			$out.=" ".$this->casa_nr;
		}
		//$out.="<br>";
		$out.=" Contacte: ";
		if ($this->getContact()->contactname!=""){
			$out.=$this->getContact()->contactname;
		}
		if ($this->getContact()->phone!=""){
			$out.=", ".$this->getContact()->phone;
		}		
		if ($this->getContact()->mobile!=""){
			$out.=", ".$this->getContact()->mobile;
		}		
									
		return $out;
	}
	function getKeywords(){
		$out="";
		$out.=$this->getScop()->name.", ";
		$out.=$this->getTipImobil()->name.", ";
		$out.=$this->getSubTipImobil()->name.", ";
		$out.=$this->getRaion()->getFullNameDescription().", ";
		$out.=$this->getLocation()->getFullNameDescription().", ";				
		return $out;
	}
	function getNext(){
		if (!isset($this->next)){
			$sql="select id from imobil where deleted=0 and";
			$sql.=" scop_id=".$this->getScop()->id." and ";
			$sql.="tipimobil_id=".$this->getTipImobil()->id." and ";
			$sql.="subtipimobil_id=".$this->getSubTipImobil()->id." and ";
			$sql.="raion_id=".$this->getRaion()->id." and ";
			if (isset($this->getLocation()->id)){
				$sql.="localitate_id=".$this->getLocation()->id." and ";
			}
			if (isset($this->getSector()->id)){
				$sql.="sector_id=".$this->getSector()->id." and ";
			}
			$sql.="id > ".$this->id;				
			$sql.=" limit 0,1";		
			$ps=DBManager::doSql($sql);
			$this->next=0;
			if (count($ps)!=0){
				foreach($ps as $p){
					$this->next=$p->id;
				}
			}
		}
		return $this->next;
	}
	function getPrevious(){
		if (!isset($this->previous)){
			$sql="select id from imobil where deleted=0 and";
			$sql.=" scop_id=".$this->getScop()->id." and ";
			$sql.="tipimobil_id=".$this->getTipImobil()->id." and ";
			$sql.="subtipimobil_id=".$this->getSubTipImobil()->id." and ";
			$sql.="raion_id=".$this->getRaion()->id." and ";
			if (isset($this->getLocation()->id)){
				$sql.="localitate_id=".$this->getLocation()->id." and ";
			}
			if (isset($this->getSector()->id)){
				$sql.="sector_id=".$this->getSector()->id." and ";
			}
			$sql.="id < ".$this->id;							
			$sql.=" order by id desc";
			$sql.=" limit 0,1";				
			$ps=DBManager::doSql($sql);
			$this->previous=0;
			if (count($ps)!=0){
				foreach($ps as $p){
					$this->previous=$p->id;
				}
			}
		}
		return $this->previous;
	}			
	function getImobilCount1(){
		$sql="select count(*) as cnt from imobil where imobil.status = 0";
		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
	}
  function getByPage($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){

       $sql="select " .
			"imobil.id, " .
			"contact_id, " .
			"tipcontact.id as tipcontact_id, " .
			"tipcontact.name as tipcontact_name, " .
			"tipcompanie.id as tipcompanie_id, " .
			"tipcompanie.name as tipcompanie_name, " .
			"contact.name as contact_name, " .
			"contact.url as contact_url, " .
			"contact.phone as contact_phone, " .
			"contact.mobile as contact_mobile, " .
			"contact.email as contact_email, " .
			"contact.note as contact_note, " .
			"imobil.scop_id, " .
			"scop.name as scop_name, " .
			"scop.menu_name as scop_menu_name, " .
			"scop.note as scop_note, " .
			"imobil.tipimobil_id, " .
			"tipimobil.name as tipimobil_name, " .
			"subtipimobil_id, " .
			"subtipimobil.name as subtipimobil_name, " .
			"tipconstructie_id, " .
			"tipconstructie.name as tipconstructie_name, " .
			"aria_totala , " .
			"aria_locuibila , " .
			"aria_lot, ".
			"aria_masura_id, ".
			"ariamasura.name as aria_masura_name, ".
			"etaj, " .
			"totaletaje, " .
			"balcoane ,".
			"an ,".
			"model_id ," .
			"model.name as model_name, ".
			"stare_id ," .
			"stare.name as stare_name, ".
			"pret ,".
			"valuta_id ," .
			"valuta.name as valuta_name, ".
			"masura_id ," .
			"masura.name as masura_name, ".
			"negociabil ,".
			"electricitate ,".
			"apeduct ,".
			"canalizare ,".
			"garaj ,".
			"subsol ,".
			"incalzire ,".
			"telefon ,".
			"gaz, ".
			"bonitate, ".
			"imobil.raion_id ," .
			"raion.name as raion_name, ".
			"raion.municipiu as municipiu, ".
			"imobil.localitate_id ," .
			"localitate.tip as localitate_tip, ".
			"localitate.name as localitate_name, ".
			"sector_id ," .
			"sector.name as sector_name, ".						
			"strada ,".
			"casa_nr ,".
			"scara_nr ,".
			"apartament_nr ," .
			"noteadresa, ".
			"centerlat ,".
			"centerlng ,".
			"imobil.zoom ,".
			"maptype ,".
			"imobil.lat ,".
			"imobil.lng ,".
			"imobil.note ,".
			"imobil.data ,".
			"image.id as image_id ".
			"from imobil " .
			"join contact on imobil.contact_id=contact.id " .
			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
			"join scop on imobil.scop_id=scop.id " .
			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
			"left join model on imobil.model_id=model.id " .
			"left join stare on imobil.stare_id=stare.id " .
			"left join valuta on imobil.valuta_id = valuta.id " .
			"left join masura on imobil.masura_id = masura.id " .
			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
			"join raion on imobil.raion_id = raion.id " .
			"left join localitate on imobil.localitate_id = localitate.id " .
			"left join sector on imobil.sector_id = sector.id " .
			"left join image on imobil.id = image.imobil_id and main=1 " .
			"where imobil.status = 0";
			if ($userid!=0){
				$sql.=" and imobil.user_id=$userid";
			}
			if ($scopid!=0){
				$sql.=" and imobil.scop_id=$scopid";
			}
			if ($tipimobilid!=0){
				$sql.=" and imobil.tipimobil_id=$tipimobilid";
			}
			if ($subtipimobilid!=0){
				$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
			}
			if ($raionid!=0){
				$sql.=" and imobil.raion_id=$raionid";
			}
			if ($localitateid!=0){
				$sql.=" and imobil.localitate_id=$localitateid";
			}
			if ($sectorid!=0){
				$sql.=" and imobil.sector_id=$sectorid";
			}
			$sql.=" order by imobil.id desc";
			$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
			
		$rs=DBManager::doSql($sql);
		return $rs;
   }
  /*
   * $type values: 
   * 0 - include si imobil si chirii
   * 1 - include imobil
   * 2 - include chirii 
   */

  function getProperties($userid=0,$type=0,$scopid=0,$tipimobilid=0,$subtipimobilid=0,$raionid=0,$localitateid=0,$sectorid=0,$page=0,$rowsperpage=10){

       $sql="select " .
		"imobil.id, " .
		"contact_id, " .
		"tipcontact.id as tipcontact_id, " .
		"tipcontact.name as tipcontact_name, " .
		"tipcompanie.id as tipcompanie_id, " .
		"tipcompanie.name as tipcompanie_name, " .
		"contact.contactname as contact_name, " .
		"contact.contacturl as contact_url, " .
		"contact.phone as contact_phone, " .
		"contact.mobile as contact_mobile, " .
		"contact.email as contact_email, " .
		"contact.notecontact as contact_note, " .
		"imobil.scop_id, " .
		"scop.name as scop_name, " .
		"scop.menu_name as scop_menu_name, " .
		"scop.note as scop_note, " .
		"imobil.tipimobil_id, " .
		"tipimobil.name as tipimobil_name, " .
		"subtipimobil_id, " .
		"subtipimobil.name as subtipimobil_name, " .
		"tipconstructie_id, " .
		"tipconstructie.name as tipconstructie_name, " .
		"aria_totala , " .
		"aria_locuibila , " .
		"aria_lot, ".
		"aria_masura_id, ".
		"ariamasura.name as aria_masura_name, ".
		"etaj, " .
		"totaletaje, " .
		"balcoane ,".
		"an ,".
		"model_id ," .
		"model.name as model_name, ".
		"stare_id ," .
		"stare.name as stare_name, ".
		"pret ,".
		"valuta_id ," .
		"valuta.name as valuta_name, ".
		"masura_id ," .
		"masura.name as masura_name, ".
		"negociabil ,".
		"electricitate ,".
		"apeduct ,".
		"canalizare ,".
		"garaj ,".
		"subsol ,".
		"incalzire ,".
		"telefon ,".
		"gaz, ".
		"bonitate, ".
		"imobil.raion_id ," .
		"raion.name as raion_name, ".
		"raion.municipiu as municipiu, ".
		"imobil.localitate_id ," .
		"localitate.tip as localitate_tip, ".
		"localitate.name as localitate_name, ".
		"sector_id ," .
		"sector.name as sector_name, ".						
		"strada ,".
		"casa_nr ,".
		"scara_nr ,".
		"apartament_nr ," .
		"noteadresa, ".
		"centerlat ,".
		"centerlng ,".
		"imobil.zoom ,".
		"maptype ,".
		"imobil.lat ,".
		"imobil.lng ,".
		"imobil.note ,".
		"imobil.data ,".
		"image.id as image_id, ".
		"image.imagepath as image_filepath ".		
		"from imobil " .
		"join contact on imobil.contact_id=contact.id " .
		"join tipcontact on contact.tipcontact_id=tipcontact.id " .
		"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
		"join scop on imobil.scop_id=scop.id " .
		"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
		"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
		"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
		"left join model on imobil.model_id=model.id " .
		"left join stare on imobil.stare_id=stare.id " .
		"left join valuta on imobil.valuta_id = valuta.id " .
		"left join masura on imobil.masura_id = masura.id " .
		"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
		"join raion on imobil.raion_id = raion.id " .
		"left join localitate on imobil.localitate_id = localitate.id " .
		"left join sector on imobil.sector_id = sector.id " .
		"left join image on imobil.id = image.reftypeid and imagemain=1 " .
		"where imobil.status = 0 and imobil.deleted=0";
		if ($type==1){
			$sql.=" and imobil.scop_id in (1,3)";
		}
		if ($type==2){
			$sql.=" and imobil.scop_id in (2,4)";
		}
		if ($userid!=0){
			$sql.=" and imobil.user_id=$userid";
		}
		if ($scopid!=0){
			$sql.=" and imobil.scop_id=$scopid";
		}
		if ($tipimobilid!=0){
			$sql.=" and imobil.tipimobil_id=$tipimobilid";
		}
		if ($subtipimobilid!=0){
			$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
		}
		if ($raionid!=0){
			$sql.=" and imobil.raion_id=$raionid";
		}
		if ($localitateid!=0){
			$sql.=" and imobil.localitate_id=$localitateid";
		}
		if ($sectorid!=0){
			$sql.=" and imobil.sector_id=$sectorid";
		}
		$sql.=" order by imobil.id desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
			
		$rs=DBManager::doSql($sql);
		return $rs;
   }   
  function getPropertiesByPage($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){

       $sql="select " .
		"imobil.id, " .
		"contact_id, " .
		"tipcontact.id as tipcontact_id, " .
		"tipcontact.name as tipcontact_name, " .
		"tipcompanie.id as tipcompanie_id, " .
		"tipcompanie.name as tipcompanie_name, " .
		"contact.contactname as contact_name, " .
		"contact.contacturl as contact_url, " .
		"contact.phone as contact_phone, " .
		"contact.mobile as contact_mobile, " .
		"contact.email as contact_email, " .
		"contact.notecontact as contact_note, " .
		"imobil.scop_id, " .
		"scop.name as scop_name, " .
		"scop.menu_name as scop_menu_name, " .
		"scop.note as scop_note, " .
		"imobil.tipimobil_id, " .
		"tipimobil.name as tipimobil_name, " .
		"subtipimobil_id, " .
		"subtipimobil.name as subtipimobil_name, " .
		"tipconstructie_id, " .
		"tipconstructie.name as tipconstructie_name, " .
		"aria_totala , " .
		"aria_locuibila , " .
		"aria_lot, ".
		"aria_masura_id, ".
		"ariamasura.name as aria_masura_name, ".
		"etaj, " .
		"totaletaje, " .
		"balcoane ,".
		"an ,".
		"model_id ," .
		"model.name as model_name, ".
		"stare_id ," .
		"stare.name as stare_name, ".
		"pret ,".
		"valuta_id ," .
		"valuta.name as valuta_name, ".
		"masura_id ," .
		"masura.name as masura_name, ".
		"negociabil ,".
		"electricitate ,".
		"apeduct ,".
		"canalizare ,".
		"garaj ,".
		"subsol ,".
		"incalzire ,".
		"telefon ,".
		"gaz, ".
		"bonitate, ".
		"imobil.raion_id ," .
		"raion.name as raion_name, ".
		"raion.municipiu as municipiu, ".
		"imobil.localitate_id ," .
		"localitate.tip as localitate_tip, ".
		"localitate.name_ro as localitate_name, ".
		"sector_id ," .
		"sector.name as sector_name, ".						
		"strada ,".
		"casa_nr ,".
		"scara_nr ,".
		"apartament_nr ," .
		"noteadresa, ".
		"centerlat ,".
		"centerlng ,".
		"imobil.zoom ,".
		"maptype ,".
		"imobil.lat ,".
		"imobil.lng ,".
		"imobil.note ,".
		"imobil.data ,".
		"image.id as image_id, ".
		"image.imagepath as image_filepath ".		
		"from imobil " .
		"join contact on imobil.contact_id=contact.id " .
		"join tipcontact on contact.tipcontact_id=tipcontact.id " .
		"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
		"join scop on imobil.scop_id=scop.id " .
		"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
		"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
		"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
		"left join model on imobil.model_id=model.id " .
		"left join stare on imobil.stare_id=stare.id " .
		"left join valuta on imobil.valuta_id = valuta.id " .
		"left join masura on imobil.masura_id = masura.id " .
		"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
		"join raion on imobil.raion_id = raion.id " .
		"left join localitate on imobil.localitate_id = localitate.id " .
		"left join sector on imobil.sector_id = sector.id " .
		"left join image on imobil.id = image.reftypeid and imagemain=1 " .
		"where imobil.status = 0 and imobil.deleted=0";
		if ($userid!=0){
			$sql.=" and imobil.user_id=$userid";
		}
		if ($imobilsauchirie==0) {
			$sql.=" and imobil.scop_id in (1,3)"; //imobil
		} else {
			$sql.=" and imobil.scop_id in (2,4)"; //chirie
		}
		if ($scopid!=0){
			$sql.=" and imobil.scop_id=$scopid";
		}
		if ($tipimobilid!=0){
			$sql.=" and imobil.tipimobil_id=$tipimobilid";
		}
		if ($subtipimobilid!=0){
			$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
		}
		if ($raionid!=0){
			$sql.=" and imobil.raion_id=$raionid";
		}
		if ($localitateid!=0){
			$sql.=" and (imobil.localitate_id=$localitateid";
			$l=new Location();
			$l->loadById($localitateid);
			$sql.=" or (6371*acos(cos(radians($l->lat))*cos(radians(imobil.lat))*cos(radians(imobil.lng)-radians($l->lng))+sin(radians($l->lat))*sin(radians(imobil.lat))))<=10)";
			
		}
		if ($sectorid!=0){
			$sql.=" and imobil.sector_id=$sectorid";
		}
		$sql.=" order by imobil.data desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
			
		$rs=DBManager::doSql($sql);
		return $rs;
   }
   function getPropertiesByCount($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid){
   
   	$sql="select count(*) as cnt " .
   			"from imobil " .
   			"join contact on imobil.contact_id=contact.id " .
   			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
   			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
   			"join scop on imobil.scop_id=scop.id " .
   			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
   			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
   			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
   			"left join model on imobil.model_id=model.id " .
   			"left join stare on imobil.stare_id=stare.id " .
   			"left join valuta on imobil.valuta_id = valuta.id " .
   			"left join masura on imobil.masura_id = masura.id " .
   			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
   			"join raion on imobil.raion_id = raion.id " .
   			"left join localitate on imobil.localitate_id = localitate.id " .
   			"left join sector on imobil.sector_id = sector.id " .
   			"left join image on imobil.id = image.reftypeid and imagemain=1 " .
   			"where imobil.status = 0 and imobil.deleted=0";
   	if ($userid!=0){
   		$sql.=" and imobil.user_id=$userid";
   	}
   	if ($imobilsauchirie==0) {
   		$sql.=" and imobil.scop_id in (1,3)"; //imobil
   	} else {
   		$sql.=" and imobil.scop_id in (2,4)"; //chirie
   	}
   	if ($scopid!=0){
   		$sql.=" and imobil.scop_id=$scopid";
   	}
   	if ($tipimobilid!=0){
   		$sql.=" and imobil.tipimobil_id=$tipimobilid";
   	}
   	if ($subtipimobilid!=0){
   		$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
   	}
   	if ($raionid!=0){
   		$sql.=" and imobil.raion_id=$raionid";
   	}
   	if ($localitateid!=0){
   		$sql.=" and imobil.localitate_id=$localitateid";
   	}
   	if ($sectorid!=0){
   		$sql.=" and imobil.sector_id=$sectorid";
   	}
   	//$sql.=" order by imobil.data desc";
   	//$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
   		
   	$rs=DBManager::doSql($sql);
   	$cnt=0;
	foreach($rs as $r){
		$cnt=$r->cnt;
	}
	return $cnt;	
   }  
   function getImobilByPage($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){
   
   	$sql="select " .
   			"imobil.id, " .
   			"contact_id, " .
   			"tipcontact.id as tipcontact_id, " .
   			"tipcontact.name as tipcontact_name, " .
   			"tipcompanie.id as tipcompanie_id, " .
   			"tipcompanie.name as tipcompanie_name, " .
   			"contact.contactname as contact_name, " .
   			"contact.contacturl as contact_url, " .
   			"contact.phone as contact_phone, " .
   			"contact.mobile as contact_mobile, " .
   			"contact.email as contact_email, " .
   			"contact.notecontact as contact_note, " .
   			"imobil.scop_id, " .
   			"scop.name as scop_name, " .
   			"scop.menu_name as scop_menu_name, " .
   			"scop.note as scop_note, " .
   			"imobil.tipimobil_id, " .
   			"tipimobil.name as tipimobil_name, " .
   			"subtipimobil_id, " .
   			"subtipimobil.name as subtipimobil_name, " .
   			"tipconstructie_id, " .
   			"tipconstructie.name as tipconstructie_name, " .
   			"aria_totala , " .
   			"aria_locuibila , " .
   			"aria_lot, ".
   			"aria_masura_id, ".
   			"ariamasura.name as aria_masura_name, ".
   			"etaj, " .
   			"totaletaje, " .
   			"balcoane ,".
   			"an ,".
   			"model_id ," .
   			"model.name as model_name, ".
   			"stare_id ," .
   			"stare.name as stare_name, ".
   			"pret ,".
   			"valuta_id ," .
   			"valuta.name as valuta_name, ".
   			"masura_id ," .
   			"masura.name as masura_name, ".
   			"negociabil ,".
   			"electricitate ,".
   			"apeduct ,".
   			"canalizare ,".
   			"garaj ,".
   			"subsol ,".
   			"incalzire ,".
   			"telefon ,".
   			"gaz, ".
   			"bonitate, ".
   			"imobil.raion_id ," .
   			"raion.name as raion_name, ".
   			"raion.municipiu as municipiu, ".
   			"imobil.localitate_id ," .
   			"localitate.tip as localitate_tip, ".
   			"localitate.name_ro as localitate_name, ".
   			"sector_id ," .
   			"sector.name as sector_name, ".
   			"strada ,".
   			"casa_nr ,".
   			"scara_nr ,".
   			"apartament_nr ," .
   			"noteadresa, ".
   			"centerlat ,".
   			"centerlng ,".
   			"imobil.zoom ,".
   			"maptype ,".
   			"imobil.lat ,".
   			"imobil.lng ,".
   			"imobil.note ,".
   			"imobil.data ,".
   			"image.id as image_id, ".
   			"image.imagepath as image_filepath ".
   			"from imobil " .
   			"join contact on imobil.contact_id=contact.id " .
   			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
   			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
   			"join scop on imobil.scop_id=scop.id " .
   			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
   			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
   			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
   			"left join model on imobil.model_id=model.id " .
   			"left join stare on imobil.stare_id=stare.id " .
   			"left join valuta on imobil.valuta_id = valuta.id " .
   			"left join masura on imobil.masura_id = masura.id " .
   			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
   			"join raion on imobil.raion_id = raion.id " .
   			"left join localitate on imobil.localitate_id = localitate.id " .
   			"left join sector on imobil.sector_id = sector.id " .
   			"left join image on imobil.id = image.reftypeid and imagemain=1 " .
   			"where imobil.status = 0 and imobil.deleted=0 and imobil.scop_id in (1,3)";
   	if ($userid!=0){
   		$sql.=" and imobil.user_id=$userid";
   	}
   	if ($scopid!=0){
   		$sql.=" and imobil.scop_id=$scopid";
   	}
   	if ($tipimobilid!=0){
   		$sql.=" and imobil.tipimobil_id=$tipimobilid";
   	}
   	if ($subtipimobilid!=0){
   		$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
   	}
   	if ($raionid!=0){
   		$sql.=" and imobil.raion_id=$raionid";
   	}
   	if ($localitateid!=0){
   		$sql.=" and imobil.localitate_id=$localitateid";
   	}
   	if ($sectorid!=0){
   		$sql.=" and imobil.sector_id=$sectorid";
   	}
   	$sql.=" order by imobil.data desc";
   	$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
   		
   	$rs=DBManager::doSql($sql);
   	return $rs;
   }
    
  function getChirieByPage($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){

       $sql="select " .
		"imobil.id, " .
		"contact_id, " .
		"tipcontact.id as tipcontact_id, " .
		"tipcontact.name as tipcontact_name, " .
		"tipcompanie.id as tipcompanie_id, " .
		"tipcompanie.name as tipcompanie_name, " .
		"contact.contactname as contact_name, " .
		"contact.contacturl as contact_url, " .
		"contact.phone as contact_phone, " .
		"contact.mobile as contact_mobile, " .
		"contact.email as contact_email, " .
		"contact.notecontact as contact_note, " .
		"imobil.scop_id, " .
		"scop.name as scop_name, " .
		"scop.menu_name as scop_menu_name, " .
		"scop.note as scop_note, " .
		"imobil.tipimobil_id, " .
		"tipimobil.name as tipimobil_name, " .
		"subtipimobil_id, " .
		"subtipimobil.name as subtipimobil_name, " .
		"tipconstructie_id, " .
		"tipconstructie.name as tipconstructie_name, " .
		"aria_totala , " .
		"aria_locuibila , " .
		"aria_lot, ".
		"aria_masura_id, ".
		"ariamasura.name as aria_masura_name, ".
		"etaj, " .
		"totaletaje, " .
		"balcoane ,".
		"an ,".
		"model_id ," .
		"model.name as model_name, ".
		"stare_id ," .
		"stare.name as stare_name, ".
		"pret ,".
		"valuta_id ," .
		"valuta.name as valuta_name, ".
		"masura_id ," .
		"masura.name as masura_name, ".
		"negociabil ,".
		"electricitate ,".
		"apeduct ,".
		"canalizare ,".
		"garaj ,".
		"subsol ,".
		"incalzire ,".
		"telefon ,".
		"gaz, ".
		"bonitate, ".
		"imobil.raion_id ," .
		"raion.name as raion_name, ".
		"raion.municipiu as municipiu, ".
		"imobil.localitate_id ," .
		"localitate.tip as localitate_tip, ".
		"localitate.name_ro as localitate_name, ".
		"sector_id ," .
		"sector.name as sector_name, ".						
		"strada ,".
		"casa_nr ,".
		"scara_nr ,".
		"apartament_nr ," .
		"noteadresa, ".
		"centerlat ,".
		"centerlng ,".
		"imobil.zoom ,".
		"maptype ,".
		"imobil.lat ,".
		"imobil.lng ,".
		"imobil.note ,".
		"imobil.data ,".
		"image.id as image_id ".
		"from imobil " .
		"join contact on imobil.contact_id=contact.id " .
		"join tipcontact on contact.tipcontact_id=tipcontact.id " .
		"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
		"join scop on imobil.scop_id=scop.id " .
		"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
		"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
		"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
		"left join model on imobil.model_id=model.id " .
		"left join stare on imobil.stare_id=stare.id " .
		"left join valuta on imobil.valuta_id = valuta.id " .
		"left join masura on imobil.masura_id = masura.id " .
		"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
		"join raion on imobil.raion_id = raion.id " .
		"left join localitate on imobil.localitate_id = localitate.id " .
		"left join sector on imobil.sector_id = sector.id " .
		"left join image on imobil.id = image.reftypeid and imagemain=1 " .
		"where imobil.status = 0 and imobil.deleted=0 and imobil.scop_id in (2,4)";
		if ($userid!=0){
			$sql.=" and imobil.user_id=$userid";
		}
		if ($scopid!=0){
			$sql.=" and imobil.scop_id=$scopid";
		}
		if ($tipimobilid!=0){
			$sql.=" and imobil.tipimobil_id=$tipimobilid";
		}
		if ($subtipimobilid!=0){
			$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
		}
		if ($raionid!=0){
			$sql.=" and imobil.raion_id=$raionid";
		}
		if ($localitateid!=0){
			$sql.=" and imobil.localitate_id=$localitateid";
		}
		if ($sectorid!=0){
			$sql.=" and imobil.sector_id=$sectorid";
		}
		$sql.=" order by imobil.data desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
			
		$rs=DBManager::doSql($sql);
		return $rs;
   }		   		
  function getByPageCount($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid){
       $sql="select count(*) as cnt " .
			"from imobil " .
			"join contact on imobil.contact_id=contact.id " .
			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
			"join scop on imobil.scop_id=scop.id " .
			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
			"left join model on imobil.model_id=model.id " .
			"left join stare on imobil.stare_id=stare.id " .
			"left join valuta on imobil.valuta_id = valuta.id " .
			"left join masura on imobil.masura_id = masura.id " .
			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
			"join raion on imobil.raion_id = raion.id " .
			"left join localitate on imobil.localitate_id = localitate.id " .
			"left join sector on imobil.sector_id = sector.id " .
			"left join image on imobil.id = image.reftypeid and imagemain=1 " .
			"where imobil.status = 0";
			if ($userid!=0){
				$sql.=" and imobil.user_id=$userid";
			}
			if ($scopid!=0){
				$sql.=" and imobil.scop_id=$scopid";
			}
			if ($tipimobilid!=0){
				$sql.=" and imobil.tipimobil_id=$tipimobilid";
			}
			if ($subtipimobilid!=0){
				$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
			}
			if ($raionid!=0){
				$sql.=" and imobil.raion_id=$raionid";
			}
			if ($localitateid!=0){
				$sql.=" and imobil.localitate_id=$localitateid";
			}
			if ($sectorid!=0){
				$sql.=" and imobil.sector_id=$sectorid";
			}
			//$sql.=" order by imobil.id desc";
			//$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
			
		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
   }
  public function get3ImobilWithImages(){
  	$sql="select imobil.* from imobil inner join image on imobil.id=image.reftypeid and image.reftype='i' and image.imagemain=1 where imobil.deleted=0 and scop_id in (1,3) order by imobil.id desc limit 0,3";
  	return $this->getResult($this->sql($sql));
  }
  public function get3ChirieWithImages(){
  	$sql="select imobil.* from imobil inner join image on imobil.id=image.reftypeid and image.reftype='c' and image.imagemain=1 where imobil.deleted=0 and scop_id in (2,4) order by imobil.id desc limit 0,3";
  	return $this->getResult($this->sql($sql));
  }  
  public static function getImobilCount($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid){
       $sql="select count(*) as cnt " .
			"from imobil " .
			"join contact on imobil.contact_id=contact.id " .
			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
			"join scop on imobil.scop_id=scop.id " .
			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
			"left join model on imobil.model_id=model.id " .
			"left join stare on imobil.stare_id=stare.id " .
			"left join valuta on imobil.valuta_id = valuta.id " .
			"left join masura on imobil.masura_id = masura.id " .
			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
			"join raion on imobil.raion_id = raion.id " .
			"left join localitate on imobil.localitate_id = localitate.id " .
			"left join sector on imobil.sector_id = sector.id " .
			"left join image on imobil.id = image.reftypeid and imagemain=1 " .
			"where imobil.status = 0 and imobil.scop_id in (1,3)";
			if ($userid!=0){
				$sql.=" and imobil.user_id=$userid";
			}
			if ($scopid!=0){
				$sql.=" and imobil.scop_id=$scopid";
			}
			if ($tipimobilid!=0){
				$sql.=" and imobil.tipimobil_id=$tipimobilid";
			}
			if ($subtipimobilid!=0){
				$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
			}
			if ($raionid!=0){
				$sql.=" and imobil.raion_id=$raionid";
			}
			if ($localitateid!=0){
				$sql.=" and imobil.localitate_id=$localitateid";
			}
			if ($sectorid!=0){
				$sql.=" and imobil.sector_id=$sectorid";
			}

		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
   }	
  public static function getChirieCount($userid,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid){
       $sql="select count(*) as cnt " .
			"from imobil " .
			"join contact on imobil.contact_id=contact.id " .
			"join tipcontact on contact.tipcontact_id=tipcontact.id " .
			"left join tipcompanie on contact.tipcompanie_id=tipcompanie.id " .
			"join scop on imobil.scop_id=scop.id " .
			"join tipimobil on imobil.tipimobil_id=tipimobil.id " .
			"left join subtipimobil on imobil.subtipimobil_id=subtipimobil.id " .
			"left join tipconstructie on imobil.tipconstructie_id=tipconstructie.id " .
			"left join model on imobil.model_id=model.id " .
			"left join stare on imobil.stare_id=stare.id " .
			"left join valuta on imobil.valuta_id = valuta.id " .
			"left join masura on imobil.masura_id = masura.id " .
			"left join ariamasura on imobil.aria_masura_id = ariamasura.id " .
			"join raion on imobil.raion_id = raion.id " .
			"left join localitate on imobil.localitate_id = localitate.id " .
			"left join sector on imobil.sector_id = sector.id " .
			"left join image on imobil.id = image.reftypeid and image.reftype='i' and imagemain=1 " .
			"where imobil.status = 0 and imobil.scop_id in (2,4)";
			if ($userid!=0){
				$sql.=" and imobil.user_id=$userid";
			}
			if ($scopid!=0){
				$sql.=" and imobil.scop_id=$scopid";
			}
			if ($tipimobilid!=0){
				$sql.=" and imobil.tipimobil_id=$tipimobilid";
			}
			if ($subtipimobilid!=0){
				$sql.=" and imobil.subtipimobil_id=$subtipimobilid";
			}
			if ($raionid!=0){
				$sql.=" and imobil.raion_id=$raionid";
			}
			if ($localitateid!=0){
				$sql.=" and imobil.localitate_id=$localitateid";
			}
			if ($sectorid!=0){
				$sql.=" and imobil.sector_id=$sectorid";
			}

		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
	}
	function getYouTubeId(){
		return $this->getQueryParamFromUrl("v",$this->youtubeurl);
	}		
}

?>
