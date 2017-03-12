<?php
class Sectiidevot extends DBManager {
	public $id;
	public $sectie_id;
	public $tara;
	public $circumscriptie_id;	
	public $raion_id;
	public $circumscriptie;
	public $comuna_id;
	public $localitate_id;
	public $localitate;
	public $sectie_nr;
	public $hotar;
	public $adresa;
	public $sediu;
	public $companie;
	public $strada;
	public $telefon;
	public $lat;
	public $lng;
	public $maptype;
	public $zoom;
	public $data;
	public $contor;
	function getTableName(){
		return "ap_sectiidevot";
	}
	
	function getAllSectii(){
		$sql="select sv.id, sv.sectie_id, sv.lat, sv.lng, sv.localitate, sv.adresa, v as total_voturi, s.voturi as s_voturi, d.voturi as d_voturi, (case when d.voturi>s.voturi then 8 else 7 end) as winner from ap_prezenta p inner join (select v.sectie_id, v.voturi from ap_voturi v where v.candidat_id=7 and tur=2) as s on p.sectie_id=s.sectie_id inner join (select v.sectie_id, v.voturi from ap_voturi v where v.candidat_id=8 and tur=2) as d on p.sectie_id=d.sectie_id inner join ap_sectiidevot sv on p.sectie_id=sv.sectie_id where p.tur=2 and p.k='h'";
		$o=Sectiidevot::doSql($sql);
		return $o;		
	}
	function getData(){
		return substr($this->data,0,10);
	}
}
?>
