<?php
require_once(__DIR__ . '/../main/loader.php');


class IndexDictionarWebPage extends MainWebPage {

	private $dictionarJudet;
	private $dictionarTip;
	private $dictionar;
	function __construct(){
		parent::__construct();
		$this->setLogoTitle("Dictionar Geografic al Basarabiei an. 1904 de Zamfir Arbore");
		$this->dictionarJudet=new DictionarJudet();
		$this->dictionarTip=new DictionarTip();
		$this->dictionar=new Dictionar();
		$this->create();		
	}
	function actionDefault(){
		//$this->setLogoTitle("Localitati din Republica Moldova");
		$this->setTitle("Dictionar Geografic al Basarabiei an. 1904 de Zamfir Arbore");
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Judete:",$this->getMenuJudet()));	
		$this->setCenterContainer($this->getGroupBoxH2("Cauta in Dictionar:",$this->getMainSearchDictionar()));
		//$this->setCenterContainer($this->getGroupBoxH2("Despre Dictionar:",$this->getDictionarDescriere()));		
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Tip:",$this->getMenuTip()));
		$this->show();
	}
	function actionViewJudet(){
		if (isset($this->id)){
			$dj=new DictionarJudet();
			$dj->loadById($this->id);
			$this->dj=$dj;		
			
			//$this->setTitle('Judetul '.$this->dj->nume);
				
			$this->setCenterContainer($this->getDictionarByJudet());
		
			$this->setLeftContainer($this->getGroupBoxH3("Filtru Judet",$this->getMenuJudet()));
			$this->setLeftContainer($this->getGroupBoxH3("Cauta in Dictionar:",$this->getSearchDictionar()));
			$this->setRightContainer($this->getGroupBoxH3("Filtru Tip",$this->getMenuTip()));
			$this->show();
		} else{
			$this->redirect(Config::$dictionarsite."/index.php");
		}		
	}
	function actionViewTip(){
		if (isset($this->id)){
			$dt=new DictionarTip();
			$dt->loadById($this->id);
			$this->dt=$dt;
				
			//$this->setTitle($this->dt->nume);
	
			$this->setCenterContainer($this->getDictionarByTip());
	
			$this->setLeftContainer($this->getGroupBoxH3("Filtru Judet",$this->getMenuJudet()));
			$this->setLeftContainer($this->getGroupBoxH3("Cauta in Dictionar:",$this->getSearchDictionar()));
			$this->setRightContainer($this->getGroupBoxH3("Filtru Tip",$this->getMenuTip()));
			$this->show();
		} else{
			$this->redirect(Config::$dictionarsite."/index.php");
		}
	}	
	function actionViewDictionar(){
	
		if (isset($this->id)){
			$d=new Dictionar();
			$d->loadById($this->id);
			$this->dictionar=$d;
			$d->count();
		} else{			
			$this->redirect(Config::$dictionarsite."/index.php");
		}
		
		
		$this->setTitle($this->dictionar->denumire.' '.$this->dictionar->tip.' in Judetul '.$this->dictionar->judet);
		$this->setCenterContainer($this->getDictionar());
		$this->setCenterContainer($this->getSystemDetails($d));
	
		$c='<a name="10"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'d',$d->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru Judet:",$this->getMenuJudet()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta in Dictionar:",$this->getSearchDictionar()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru Tip:",$this->getMenuTip()));
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
	
	function getMenuJudet(){
		$out='<ul class="leftmenulist">';
		$djs=$this->dictionarJudet->getAll();
		if (count($djs)!=0){
		foreach ($djs as $dj){
			$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewjudet&id=".$dj->id).'">'.$dj->nume.'</a></li>';
		}
		}
		$out.='</ul>';
		return $out;
	}
	function getMenuTip(){
		$out='<ul class="leftmenulist">';
		$dts=$this->dictionarTip->getAll();
		if (count($dts)!=0){
			foreach ($dts as $dt){
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtip&id=".$dt->id).'">'.$dt->nume.'</a></li>';
			}
		}		
		$out.='</ul>';
		return $out;
	}
	function getDictionarDescriere(){
		$out='Text ceva';
		return $out;
	}			
	function getDictionar(){
		$out="";
		$o1s='<a name="1"></a>'.$this->dictionar->denumire;
		$o1b=$this->dictionar->descriere;
		$o1f='';
		if (!empty($this->dictionar->localitate_id)){
			$l=new Location();
			$l->loadById($this->dictionar->localitate_id);
			$o1f.='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$l->id).'">Vezi aici mai mult despre '.$l->getFullNameDescription().'</a>';
		}				
		$out.=$this->getGroupBoxH3($o1s,$o1b,$o1f);
		return $out;
	}
	function getDictionarByJudet(){
		$d=new Dictionar();
		$ds=$d->getAll("judet_id=".$this->dj->id);
		$out="";
		$o1b="";
		if ($this->dj->id!=1){
			$o1s='Elemente cartografice in Judetul '.$this->dj->nume;
		} else {
			$o1s='Alte elemente cartografice in Basarabia';
		}
		
		$this->setTitle($o1s);
		
		foreach ($ds as $d){
			$o1b.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdictionar&id=".$d->id).'">'.$d->denumire.', '.$d->tip.' in Judetul '.$d->judet.'</a><br>';
		}
		
		
		$out.=$this->getGroupBoxH3($o1s,$o1b);
		return $out;
	}
	function getDictionarByTip(){
		$d=new Dictionar();
		$ds=$d->getAll("tip_id=".$this->dt->id);
		$out="";
		$o1b="";
		
		if ($this->dt->id!=1){
			$o1s='Lista elementelor cartografice de tip '.$this->dt->nume;
		} else {
			$o1s='Alte elemente cartografice in Basarabia';
		}
		
		$this->setTitle($o1s);
		
		foreach ($ds as $d){
			$o1b.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdictionar&id=".$d->id).'">'.$d->denumire.', '.$d->tip.' in Judetul '.$d->judet.'</a><br>';
		}
	
	
		$out.=$this->getGroupBoxH3($o1s,$o1b);
		return $out;
	}
	function getSystemDetails($d){
		$out='<div>';
		$out.='<div id="property-view-dateq" style="float:left">';
		$out.='Data publicarii: '.$d->getData();
		$out.='</div>';
		$out.='<div id="property-view-dateq" style="float:right">';
		$out.='Vizualizari: '.$d->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $this->getGroupBoxH3('Alte date:',$out);
	}															
}

$n=new IndexDictionarWebPage();
?>
