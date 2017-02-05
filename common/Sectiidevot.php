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
	function getTableName(){
		return "ap_sectiidevot";
	}

}
?>
