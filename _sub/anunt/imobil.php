<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
/*
 * This page is to redirect urls like http://anunt.casata.md/imobil.php?fscop=1&ftip=0&fraionid=100&flocalitateid=0&fsectorid=0&fsubtipimobilid=0 to news urls
 */
class ImobilWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		if (!isset($this->fscop)){
			$this->fscop=0;
		}
		if (!isset($this->ftip)){
			$this->ftip=0;
		}
		if (!isset($this->fsubtipimobilid)){
			$this->fsubtipimobilid=0;
		}			
		if (!isset($this->fraionid)){
			$this->fraionid=0;
		}
		if (!isset($this->flocalitateid)){
			$this->flocalitateid=0;
		}	
		if (!isset($this->fsectorid)){
			$this->fsectorid=0;
		}	
									
		$link=Config::$imobilsite;
		if (($this->fscop==2)||($this->fscop==4)){
			$link=Config::$chiriesite.'/index.php?scopid='.$this->fscop.'&tipimobil='.$this->ftip.'&subtipimobil='.$this->fsubtipimobilid.'&raionid='.$this->fraionid.'&locationid='.$this->flocalitateid.'&sectorid='.$this->fsectorid;
		}else {
			$link=Config::$imobilsite.'/index.php?scopid='.$this->fscop.'&tipimobil='.$this->ftip.'&subtipimobil='.$this->fsubtipimobilid.'&raionid='.$this->fraionid.'&locationid='.$this->flocalitateid.'&sectorid='.$this->fsectorid;
		}
		$this->redirect($link);
	}
}
$n=new ImobilWebPage();
?>
