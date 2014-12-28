<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Dictionar extends DBManager {
	public $id;
	public $localitate_id;
	public $descriere;
	public $denumire;
	public $tip_id;
	public $tip;
	public $judet_id;
	public $judet;
	public $populatie;
	public $data;
	public $ignored;	
	public $contor;
	function getData(){
		//return System::getDate(date_parse($this->data));
		return substr($this->data,0,10);
	}	
}

?>
