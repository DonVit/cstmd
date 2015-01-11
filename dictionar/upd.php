<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');


class IndexDictionarWebPage extends MainWebPage {

	private $dictionarJudet;
	private $dictionarTip;
	private $dictionar;
	function __construct(){
		parent::__construct();
		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;hl=ro&amp;sensor=false&amp;key=".Config::getMapKey($this->getServerName()));
		//$this->setCSS("style/maps.css");
		//$this->setJavascript("js/scripts.js");
		$this->setLogoTitle("Dictionar Geografic al Basarabiei an. 1904 de Zamfir Arbore");
		$this->dictionar=new Dictionar();
		$this->create();		
	}

	function actionDefault(){
	
		
		$d=new Dictionar();
		$ds=$d->getAll("populatie!='' and localitate_id=0 and ignored=0 and judet_id not in (2,7)");
		$this->dictionar=$ds[0];
			
		
		
		$this->setTitle($this->dictionar->denumire.' '.$this->dictionar->tip.' in Judetul '.$this->dictionar->judet);
		$this->setCenterContainer($this->getDictionar());
		$this->setCenterContainer($this->getGroupBoxH3("Cauta Localitatea:",$this->getSearchLocation()));
		$this->setCenterContainer($this->getGroupBoxH3("Cauta in Dictionar:",$this->getSearchDictionar()));

		$this->show();
	}	
			

														
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:600px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:198px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
		
	function getDictionar(){
		$out="";
		$r=100;
		$l=101;
		
		Logger::setLogs($_POST);
		if (!empty($_POST['raion_id'])){
			$r=$_POST['raion_id'];
		}
		if (!empty($_POST['localitate_id'])){
			$l=$_POST["localitate_id"];
		}	
		if (!empty($_POST['ignoreformpost'])){
			$this->dictionar->doSql("update dictionar set ignored=1 where id=".$this->dictionar->id);
			$this->redirect(Config::$dictionarsite."/upd.php");
		}
		if (!empty($_POST['saveformpost'])){
			//$this->dictionar->localitate_id=$l;
			//$this->dictionar->save();
			$this->dictionar->doSql("update dictionar set localitate_id=".$l." where id=".$this->dictionar->id);
			$this->redirect(Config::$dictionarsite."/upd.php");						
		}		
		
		$o1s=$this->dictionar->denumire;
		$o1b=$this->dictionar->descriere;
		$o1f='';
		$o1f.='<form id="frmWizard" name="frmWizard" method="POST">';
		$o1f.='<table class="property-table" align="center" style="width: 100%;">';
		$o1f.='<tr>';
		$o1f.='<td class="property-name" style="width: 30%;">ID:</td>';
		$o1f.='<td style="width: 70%;">'.$this->dictionar->id.'</td>';
		$o1f.='</tr>';
		$o1f.='<tr>';
		$o1f.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$o1f.='<td style="width: 70%;">'.Raion::getRaionDropDown($r,"width:200px;").'</td>';
		$o1f.='</tr>';		
		$o1f.='<tr>';
		$o1f.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$o1f.='<td style="width: 70%;">'.Location::getLocationDropDown($r,$l,"width:200px;").'</td>';
		$o1f.='</tr>';
		$o1f.='<tr>';
		$o1f.='<td class="property-name" style="width: 30%;">Ignora:</td>';
		$o1f.='<td style="width: 70%;"><input type="submit" name="ignoreformpost" class="button" style="width:60px;" value="Ignore"></td>';
		$o1f.='</tr>';
		$o1f.='<tr>';
		$o1f.='<td class="property-name" style="width: 30%;">Salveaza:</td>';
		$o1f.='<td style="width: 70%;"><input type="submit" name="saveformpost" class="button" style="width:60px;" value="Save"></td>';
		$o1f.='</tr>';				
				
		$o1f.='</table>';
		$o1f.='</form>';
		$out.=$this->getGroupBoxH3($o1s,$o1b,$o1f);
		return $out;
	}												
}

$n=new IndexDictionarWebPage();
?>
