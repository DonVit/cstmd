<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
/*
 * This page is to redirect urls like http://anunt.casata.md/imobil.php?fscop=1&ftip=0&fraionid=100&flocalitateid=0&fsectorid=0&fsubtipimobilid=0 to news urls
 */
class ImobilViewWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$link=Config::$imobilsite;
		if (isset($this->id)){
			$p=new Property();
			$p->loadById($this->id);
			if (isset($p->id)){
				if (($p->scop_id==2)||($p->scop_id==4)){
					$link=Config::$chiriesite.'/property.php?id='.$p->id;
				}else{
					$link=Config::$imobilsite.'/property.php?id='.$p->id;
				}
			}
		}
		$this->redirect($link);
	}
}
$n=new ImobilViewWebPage();
?>
