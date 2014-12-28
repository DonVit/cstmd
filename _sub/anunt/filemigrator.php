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
	
	private $counter;
	private $rs;
	private $out;
	function __construct(){
		parent::__construct();
		$this->counter=0;
		$this->out='<table>';
		$t="Files migration";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		
		$sql="SELECT scop_id, filepath FROM  `imobil` INNER JOIN image ON imobil.id = image.imobil_id";
		$rs=DBManager::dosql($sql);
		
		foreach ($rs as $r){
			if (($r->scop_id==1)||($r->scop_id==3)){
				$this->copyfile('data/'.$r->filepath,'../imobil/data/'.$r->filepath);
				$this->copyfile('data/t'.$r->filepath,'../imobil/data/t'.$r->filepath);
			} else {
				$this->copyfile('data/'.$r->filepath,'../chirie/data/'.$r->filepath);
				$this->copyfile('data/t'.$r->filepath,'../chirie/data/t'.$r->filepath);
			}
		}
		$this->out.='</table>';
		$this->show();

	}
	function copyfile($src,$dst){
		
		$t='<tr><td>'.$src.'</td><td>'.$dst.'</td>';
		if (!file_exists($dst)){
			if (file_exists($src)){
				if (copy($src,$dst)){
					$this->counter=$this->counter+1;
					$t.='<td>copied</td>';
				}else{
					$t.='<td>fail to copy</td>';
				}
			} else {
				$t.='<td>src dose not exists</td>';
			} 			
		} else {
			$t.='<td>dst exists</td>';
		}
		$t.='</tr>';
		
		$this->out.=$t; 
	}
	function show($html="IndexWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="group1" class="maingroupbox">';
		$out.='<h2 class="groupheader_h2">Done! Au fost copiate '.$this->counter.' din '.(count($this->rs)*2).'</h2>';
		$out.=$this->out;
		$out.='</div>';	
		$out.='</div>';		
		MainWebPage::show($out);
	}	
}
$n=new ImobilWebPage();
?>
