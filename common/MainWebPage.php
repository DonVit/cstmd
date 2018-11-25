<?php
class MainWebPage extends WebPage {
	private $logotitle="logotitle";
	private $css="";
	private $javascript="";
	private $footerjavascript="";
	private $bodytag="<body>";
	private $leftcontainer;
	private $centercontainer;
	private $rightcontainer;
	private $ogp;
	public function __construct() {
		parent::__construct();
		
		$this->setCSS(Config::$commonsite."/style/reset.css");
		$this->setCSS(Config::$commonsite."/style/fonts.css");		
		$this->setCSS(Config::$commonsite."/style/base.css");			

		$this->setCSS(Config::$commonsite."/style/common.css");
		$this->setJavascript(Config::$commonsite."/js/scripts.js");
		$this->setJavascript("https://www.google.com/recaptcha/api.js?hl=".$this->getLang()->name);
		$this->setJavascript("https://cdn.ckeditor.com/4.4.6/basic/ckeditor.js");
	}
	
	function show($html="MainWebPageHtml"){
		$out=$this->getHeader();
		$out.=$this->getBody($html);
		$out.=$this->getFooter();
		WebPage::show($out);
	}

	function getLogoTitle(){
		return $this->logotitle;
	}
	function setLogoTitle($logotitle){
		$this->logotitle=$logotitle;
	}
	function getFavIcon(){
		return '<link  rel="icon" href="'.Config::$commonsite.'/img/favicon.ico">';
	}
	function getTheShiv(){
		$out='';
		$out.='<!--[if lt IE 9]>';
		$out.='<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>';
		$out.='<![endif]-->';
		return $out;
	}			
	function getCSS(){
		return $this->css;
	}
	function setCSS($css){
		$this->css.='<link rel="stylesheet" href="'.$css.'">';
	}
	function getJavascript(){
		return $this->javascript;
	}
	function setJavascript($javascript){
		$this->javascript.='<script src="'.$javascript.'"></script>';
	}
	function getFooterJavascript(){
		return $this->footerjavascript;
	}
	function setFooterJavascript($javascript){
		$this->footerjavascript.='<script src="'.$javascript.'"></script>';
	}	
	function getHeader(){
		$out='<!doctype html>';
		$out.='<html>';
		$out.='<head>';
		$out.='<title>'.$this->getTitle().'</title>';
		$out.='<meta charset="utf-8">';
		$out.='<meta content="'.$this->getDescription().'" name="description"/>';
		$out.='<meta content="'.$this->getKeywords().'" name="keywords"/>';
		$out.=$this->getOGP();
		//$out.='<meta name=viewport content="width=device-width, initial-scale=1">';
		$out.=$this->getTheShiv();
		$out.=$this->getFavIcon();
		$out.=$this->getCSS();
		//$out.='<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		$out.=$this->getJavascript();
		$out.=$this->getGA();
		$out.='</head>';
		$out.=$this->getBodyTag();
		$out.=$this->getFacebookSDK();
		$out.='<div id="main" class="main">';
		$out.=$this->getBanner();		
		$out.=$this->getTopMenu();	
		$out.=$this->getLogo();
		$out.=$this->getMainMenu();
		return $out;
	}
	function getTopMenu(){	
		$out='<div id="topmenu" class="container bar tophormenu">';
		$out.=$this->getLanguageMenu();	
		$out.=$this->getUserMenu();			
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';	
		return $out;
	}
	function getMainMenu(){	
		$out='<div id="mainmenu" class="container bar mainhormenu">';
		$out.='<div id="mainmenuleft" style="display:block;float:left">';
		$out.='<ul>';
		//$SelectedItem=TopMenu::getSelectedItem();
		foreach (TopMenu::getItems($this->getLang()) as $ItemKey=>$ItemValue){
			//if ($SelectedItem==$ItemKey){
			$host=parse_url($ItemKey,PHP_URL_HOST);
			//Logger::setLogs($this->getServerName()."-".$ItemKey."-".$host);	
			if ($this->getServerName()==$host){
				$out.='<li><a href="'.$ItemKey.'" style="border-bottom:2px solid #C20000;">'.$ItemValue.'</a></li>';
			} else {
				$out.='<li><a href="'.$ItemKey.'">'.$ItemValue.'</a></li>';
			}
		}
		$out.='</ul>';	
		$out.='</div>';
// 		$out.='<div id="mainmenuright" style="display:block;float:right">';
// 		$out.='<ul>';
// 		$out.='<li>'.$this->getBookmarks().'</li>';
// 		$out.='</ul>';						
// 		$out.='</div>';
		$out.='<div style="clear: both;"></div>';		
		$out.='</div>';	
		return $out;
	}	
	function getUserMenu(){	
		$out='<div id="usermenu" style="display:block;float:right">';
		$out.='<ul>';
		if (User::getCurrentUser()->name=="Anonymous"){
			$out.='<li><a href="'.Config::$accountssite.'/login.php">Login</a></li>';
			$out.='<li><a href="'.Config::$accountssite.'/register.php">Inregistrare</a></li>';
		} else {
			$out.='<li><a href="'.Config::$accountssite.'/index.php">Contul Personal</a></li>';
			$out.='<li><a href="'.Config::$accountssite.'/logout.php">Logout</a></li>';
		}
		//$out.='<div style="clear: both;"></div>';
		$out.='</ul>';	
		$out.='</div>';	
		return $out;
	}
	function getBanner(){   
		return AdsBanner::getRandomBanner();
	}
	function getLogo(){	
		$out='<div id="logo" class="container bar tophorlogo">';
		//$out.='<div id="logo-title-left"><span style="color:#000099;">casa</span><span style="color:#FFFF33;">ta</span><span style="color:#FF0000;">.md</span></div>';
		$out.='<div id="logo-title-left" style="display:block;float:left">CASATA.MD</div>';
		$out.='<div id="logo-title-right" style="display:block;float:left">'.$this->getLogoTitle().'</div>';
		$out.=$this->getFacebookButton();
		$out.=$this->getGooglePlus();		
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
	function getGooglePlus(){
		if (Config::$live){
			$out='<div id="logo-plusone" style="display:block;float:left;padding-left:100px;">';
			$out.='<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \''.$this->getLang()->name.'\'}</script>';
			$out.='<g:plusone size="medium"></g:plusone>';
			$out.='</div>';
		return $out;	
		} else {
			return '';
		}	
	}
	function getFacebookButton(){
		if (Config::$live){
			$out='<div class="fb-like" data-href="https://www.facebook.com/casata.moldova/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>';
			return $out;
		} else {
			return '';
		}
	}	
	function getFacebookSDK(){
		if (Config::$live){
			$out='<div id="fb-root"></div>';
			$out.='<script>(function(d, s, id) {';
			$out.='  var js, fjs = d.getElementsByTagName(s)[0];';
			$out.='  if (d.getElementById(id)) return;';
			$out.='  js = d.createElement(s); js.id = id;';
			$out.='  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=209032902551666";';
			$out.='  fjs.parentNode.insertBefore(js, fjs);';
			$out.='}(document, \'script\', \'facebook-jssdk\'));</script>';
			return $out;
		} else {
			return '';
		}
	}
	function getLanguageMenu(){	
		$out='<div id="langmenu" style="display:block;float:left">';
		$out.='<ul>';
		$l=new Language();
		$ls=$l->getAll();
		foreach($ls as $l){
			if ($l->name==$this->getLang()->name){
				$out.='<li>'.$l->name_long.'</li>';
			}else{
				$out.='<li><a href="'.$this->getUrlInLanguage($l->name).'">'.$l->name_long.'</a></li>';
			}
		}
		$out.='</ul>';	
		$out.='</div>';	
		return $out;
	}			
	function getBodyTag(){
		return $this->bodytag;
	}
	function setBodyTag($bodytag){
		$this->bodytag=$bodytag;
	}
	function getBody($html){
		$out='<div id="body">';
		$out.=$html;
		$out.='</div>';
		return $out;
	}
	function getFooter(){
		$out='<div id="footer" class="container bar bottomhormenu">';
		$out.=$this->getCounters();
		$out.=$this->getBottomMenu();
		$out.='</div>';
		$out.=$this->getLogs();
		$out.='</div>';
		$out.=$this->getFooterJavascript();
		$out.='</body>';
		$out.='</html>';
		return $out;		
	}
	function getBottomMenu(){	
		$out='<div id="bottommenu">';		
		$out.='<ul>';
		foreach (BottomMenu::$Items as $ItemKey=>$ItemValue){
				//$out.='<div class="bottommenu_item"><a href="/'.$ItemKey.'">'.$ItemValue.'</a></div>';
				$out.='<li><a href="'.Config::$accountssite.'/'.$ItemKey.'">'.$ItemValue.'</a></li>';
		}
		$out.='<li> | Email la: <a href="mailto:casata.md@outlook.com">casata.md@outlook.com</a></li>';	
		$out.='</ul>';
		$out.=$this->getFacebookButton();
		$out.=$this->getGooglePlus();		
		$out.='</div>';	
		$out.='<div style="clear: both;"></div>';	
		return $out;
	}
	function getCounters(){	
		$out='';
		if (Config::$live){
			$out.='<div id="counters" class="counters">';
			$out.='<!--/Start trafic.ro/-->';
			$out.='<script type="text/javascript">t_rid="casatamd";</script>';
			$out.='<script type="text/javascript" src="https://storage.trafic.ro/js/trafic.js"></script>';
			$out.='<noscript>';		
			$out.='<a href="https://www.trafic.ro/top/?rid=casatamd" target="_blank"><img border="0" alt="trafic ranking" src="https://log.trafic.ro/cgi-bin/pl.dll?rid=casatamd"/></a>';
			$out.='<a href="https://www.trafic.ro">Statistici web</a>';
			$out.='</noscript>';
			$out.='<!--/End trafic.ro/-->';
			$out.='</div>';	
			$out='';
		}
		return $out;
	}			
	function getLogs(){
		$out="";
		if (Config::$loging){
			$out.='<div id="logs">';
			//$out.='<fieldset id="logstext">';
			//$out.='<legend>Logs</legend>';
			//$out.=$this->getHeaders();			
			$out.=Logger::getLogs();
			$out.=$this->getCurrentConnections();
			//$out.='</fieldset>';
			$out.='</div>';
			$out.='<p style="font-size:85%;">(11px) Lorem ipsum dolor sit amet, consectetur.</p>';
			$out.='<div style="clear: both;"></div>';	
		}
		return $out;
	}
	function getCurrentConnections(){
		$c=new DBManager();
		$cs=$c->doSql("SHOW PROCESSLIST");
		$out="Current Connection:";
		$out.="<table>";
		foreach($cs as $c){
			$out.="<tr><td>".$c->Id."</td><td>".$c->Info."</td></tr>";
		}
		$out.="</table>";

		$out.="Session Objects:";
		$out.="<table>";
		foreach($_SESSION as $s){
			$out.="<tr><td>".$s."</td>";
		}
		$out.="</table>";
		
		return $out;
	}
	function getGA() {
		$out="";
		if (Config::$live){
			$out.="<script>";
			$out.="(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";
			$out.="ga('create', 'UA-1976884-1', 'auto');";
			$out.="ga('send', 'pageview');";
			$out.="</script>";	
		}
		return $out;
	}
	function getBookmarks($permalink="", $title="") {
		if (Config::$live){
			//addthis
			$out='<div class="addthis_toolbox addthis_default_style " style="width:120px;">';
			$out.='<a class="addthis_button_preferred_1"></a>';
			//$out.='<a class="addthis_button_preferred_2"></a>';
			//$out.='<a class="addthis_button_preferred_3"></a>';
			//$out.='<a class="addthis_button_preferred_4"></a>';
			//$out.='<a class="addthis_button_preferred_5"></a>';
			//$out.='<a class="addthis_button_preferred_6"></a>';
			//$out.='<a class="addthis_button_preferred_7"></a>';
			//$out.='<a class="addthis_button_preferred_8"></a>';
			//$out.='<a class="addthis_button_preferred_9"></a>';
			//$out.='<a class="addthis_button_preferred_10"></a>';
			$out.='<a class="addthis_button_compact"></a>';
			$out.='<a class="addthis_counter addthis_bubble_style"></a>';
			$out.='</div>';
			$out.='<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>';
			$out.='<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=donvit"></script>';
				
			return $out;
		} else {
			return '';
		}
	}
	function setLeftContainer($value=""){
		$this->leftcontainer.=$value;	
	}	
	function getLeftContainer(){
		return $this->leftcontainer;	
	}
	function setCenterContainer($value=""){
		$this->centercontainer.=$value;	
	}	
	function getCenterContainer(){
		return $this->centercontainer;	
	}
	function setRightContainer($value=""){
		$this->rightcontainer.=$value;	
	}
	function getRightContainer(){
		$out='';
		//$out.=$this->getPrivescEuWidget();
		$out.=$this->rightcontainer;
		return $out;		
	}			
	function getGroupBoxH3($header="",$body="",$footer=""){
		if ($header!=""){
			$header='<h3>'.$header.'</h3>';			
		}
		if ($footer!=""){	
			$footer='<h3>'.$footer.'</h3>';		
		}
		return $this->getGroupBoxHtml($header,$body,$footer);
	}
	function getGroupBoxH2($header="",$body="",$footer=""){
		if ($header!=""){
			$header='<h2>'.$header.'</h2>';			
		}
		if ($footer!=""){	
			$footer='<h3>'.$footer.'</h3>';		
		}
		return $this->getGroupBoxHtml($header,$body,$footer);
	}		
	function getGroupBoxH1($header="",$body="",$footer=""){
		if ($header!=""){
			$header='<h1>'.$header.'</h1>';			
		}
		if ($footer!=""){	
			$footer='<h3>'.$footer.'</h3>';		
		}
		return $this->getGroupBoxHtml($header,$body,$footer);
	}
	function getGroupBoxHtml($header="",$body="",$footer=""){
		$out='<div class="container groupbox">';
		if ($header!=""){
			$out.='<div class="container groupboxheader">';
			$out.=$header;		
			$out.='</div>';	
		}
		$out.=$body;	
		if ($footer!=""){	
			$out.='<div class="container groupboxfooter">';
			$out.=$footer;		
			$out.='</div>';
		}
		$out.='</div>';
		return $out;
	}
	function getGroupBoxWizard($header="",$body="",$footer=""){
		$out='<div class="container groupbox groupboxwizard">';
		if ($header!=""){
			$out.='<div class="container groupboxwizardheader">';
			$out.=$header;		
			$out.='</div>';	
		}
		$out.=$body;	
		if ($footer!=""){	
			$out.='<div class="container groupboxwizardfooter">';
			$out.=$footer;		
			$out.='</div>';
		}
		$out.='</div>';
		return $out;
	}
	function getGroupBoxDialog($header="",$body="",$footer=""){
		$out='<div style="padding:20px 200px;">';
		$out.='<div class="container groupbox groupboxwizard">';
		if ($header!=""){
			$out.='<div class="container groupboxwizardheader">';
			$out.=$header;		
			$out.='</div>';	
		}
		$out.=$body;	
		if ($footer!=""){	
			$out.='<div class="container groupboxwizardfooter">';
			$out.=$footer;		
			$out.='</div>';
		}
		$out.='</div>';
		$out.='</div>';
		return $out;
	}						
	function getMap($m){

		$this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js");
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript(Config::$commonsite."/js/maps.js");
		$this->setJavascript(Config::$commonsite."/js/controls.js");

		//$this->setBodyTag('<body onload="MapViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("https://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		$out='';
		if ($m->lat==0){
			$m->maptype=0;
			$m->lat=0;
			$m->lng=0;

			$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->centerlat.'" />';
			$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->centerlng.'" />';
				
		}	else {

			$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->lat.'" />';
			$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->lng.'" />';
		}
		
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$m->maptype.'" />';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$m->zoom.'" />';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$m->lat.'" />';
		$out.='<input name="lng" type="hidden" id="lng" readonly="true" class="inptdisabled" value="'.$m->lng.'" />';
		//$out.='<input name="map_title" type="hidden" id="map_title" readonly="true" class="inptdisabled" value="" />';
		//$out.='<input name="map_description" type="hidden" id="map_description" readonly="true" class="inptdisabled" value="" />';
		$out.='<div id="map" style="width: 100%;"></div>';
		return $out;
	}
	function setMap($m){

		$this->setBodyTag('<body onload="MapEditOnMapLoad()">');
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript(Config::$commonsite."/js/maps.js");
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("https://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		
		$out='';
		//$out.='<div class="form_row">';
		//$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->centerlat.'"/>';
		//$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->centerlng.'"/>';
		//$out.='<input id="maptype" name="maptype" type="hidden" value="'.$m->maptype.'"/>';
		//$out.='<input id="zoom" name="zoom" type="hidden" value="'.$m->zoom.'"/>';
		//$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$m->lat.'"/>';
		//$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$m->lng.'"/>';
		//$out.='<div id="map"></div>';
		//$out.='</div>';

		$out='';
		$out.='<div>';
		$out.='<table style="width:100%"><tr><td>';
		$out.='<div>';		
		$out.='<input id="map_title" name="map_title" type="input" value="'.(isset($m->map_title)?$m->map_title:'').'" style="margin:0px;padding-left:0px;padding-right:0px;width:99.9%">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$m->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$m->zoom.'"/>';
		$out.='<input id="lat" name="lat" type="hidden" value="'.$m->lat.'"/>';
		$out.='<input id="lng" name="lng" type="hidden" value="'.$m->lng.'"/>';
		$out.='</div>';
		$out.='</td></tr><tr><td>';
		$out.='<div id="map"></div>';
		$out.='</td></tr></table>';			
		$out.='</div>';
		return $out;	
	}
	function setAdress($a){
		$out='';
		$out.='<table class="property-table" style="width: 100%;align:center;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.Raion::getRaionDropDown($a->raion_id,"width:200px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.Location::getLocationDropDown($a->raion_id,$a->localitate_id,"width:200px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Sector:</td>';
		$out.='<td style="width: 70%;">'.Sector::getSectorDropDown($a->localitate_id,$a->sector_id,"width:200px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Strada:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="strada" name="strada" class="input" style="width:350px;" value="'.$a->strada.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Casa:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="casa_nr" name="casa_nr" class="input" style="width:200px;" value="'.$a->casa_nr.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Scara:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="scara_nr" name="scara_nr" class="input" style="width:200px;" value="'.$a->scara_nr.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Apartament:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="apartament_nr" name="apartament_nr" class="input" style="width:200px;" value="'.$a->apartament_nr.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Note la Adresa:</td>';
		$out.='<td style="width: 70%;"><textarea id="noteadresa" name="noteadresa" class="text" style="width:350px;height:62px;padding:4px;" >'.$a->noteadresa.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 
		return $out;	
	}	
	function getAdress($a){
			$out='';			
			$out.='<table class="property-table" style="width: 100%;align:center;">';
			$out.='<tr><td class="property-name" style="width: 35%;">Municipiul/Raionul:</td><td class="property-value" style="width: 65%;"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewraion&id='.$a->getRaion()->id).'" >'.$a->getRaion()->getFullName().'</a></td></tr>';			
			$out.='<tr><td class="property-name" style="width: 35%;">Oras/Sat:</td><td class="property-value" style="width: 65%;"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewlocalitate&id='.$a->getLocation()->id).'" >'.$a->getLocation()->getFullName().'</a></td></tr>';			
			$out.='<tr><td class="property-name" style="width: 35%;">Sector:</td><td class="property-value" style="width: 65%;">'.$a->getSector()->name.'</td></tr>';
			$out.='<tr><td class="property-name" style="width: 35%;">Strada:</td><td class="property-value" style="width: 65%;">'.$a->strada.'</td><td></td><td></td></tr>';
			$out.='<tr><td class="property-name" style="width: 35%;">Nr. Casa:</td><td class="property-value" style="width: 65%;">'.$a->casa_nr.'</td></tr>';
			$out.='<tr><td class="property-name" style="width: 35%;">Nr. Scara:</td><td class="property-value" style="width: 65%;">'.$a->scara_nr.'</td></tr>';
			$out.='<tr><td class="property-name" style="width: 35%;">Nr. Apartament:</td><td class="property-value" style="width: 65%;">'.$a->apartament_nr.'</td></tr>';				
			$out.='<tr><td class="property-name" style="width: 35%;">Note la adresa:</td><td class="property-value" style="width: 65%;">'.$a->noteadresa.'</td></tr>';
			$out.='</table>';		 			
			return $out;
	}		
	function setContacts($c){
		$out='';	
		$out.='<table class="property-table" style="width: 100%;align:center;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nume de contact:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="name" name="contactname" style="width: 55%;" value="'.$c->contactname.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Telefon Fix:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="phone" name="phone" style="width: 55%;" value="'.$c->phone.'"></td>';
		$out.='</tr>';		
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Telefon Mobil:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="mobile" name="mobile" style="width: 55%;" value="'.$c->mobile.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">E-mail:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="email" name="email" style="width: 55%;" value="'.$c->email.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Skype Id:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="skypeid" name="skypeid" style="width: 55%;" value="'.$c->skypeid.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Web Site:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="contacturl" name="contacturl" style="width: 55%;" value="'.$c->contacturl.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Fax:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="fax" name="fax" style="width: 55%;" value="'.$c->fax.'"></td>';
		$out.='</tr>';						
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Note de contact:</td>';
		$out.='<td style="width: 70%;"><textarea id="notecontact" name="notecontact" style="width: 70%;">'.$c->notecontact.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		return $out;	 
	}	
	function getContacts($c){
		$out='';
		$out.='<table class="property-table" style="width:100%;align:center;">';
		$out.='<tr>';
		$out.='<td style="width:70%">';					
		$out.='<table class="property-table" style="width:100%;align:center;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width:35%">Nume de contact:</td><td class="property-value" style="width:65%">'.$c->contactname.'</td>';
		$out.='</tr>';
		$out.='<tr>';			
		$out.='<td class="property-name" style="width:35%">Telefon Fix:</td><td class="property-value" style="width:65%">'.$c->phone.'</td>';		
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width:35%">Telefon Mobil:</td><td class="property-value" style="width:65%">'.$c->mobile.'</td>';
		$out.='</tr>';
		$out.='<tr>';			
		$out.='<td class="property-name" style="width:35%">E-mail:</td><td class="property-value" style="width:65%">'.$this->getEmailControl($c->email).'</td>';		
		$out.='</tr>';
		$out.='<tr>';			
		$out.='<td class="property-name" style="width:35%">Skype Id:</td><td class="property-value" style="width:65%">'.$this->getSkypeControl($c->skypeid).'</td>';		
		$out.='</tr>';				
		$out.='<tr>';
		$out.='<td class="property-name" style="width:35%">Web Site:</td><td class="property-value" style="width:65%">'.$this->getUrlControl($c->contacturl).'</td>';
		$out.='</tr>';		
		$out.='<tr>';
		$out.='<td class="property-name" style="width:35%">Fax:</td><td class="property-value" style="width:65%">'.$c->fax.'</td>';
		$out.='</tr>';		
		$out.='<tr>';				 		
		$out.='<td class="property-name" style="width:35%">Note de contact:</td><td class="property-value" style="width:65%">'.$c->notecontact.'</td>';
		$out.='</tr>';	
		$out.='</table>'; 
		$out.='</td>';
		$out.='<td style="width:30%">';
		$out.=$this->getContactsQRCode($c);
		$out.='</td>';
		$out.='</tr>';				
		$out.='</table>';					
	
		return $out;	 
	}
	function getContactsQRCode($c){
		$qrcodeurl='https://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=MECARD:';
		//Name
		$qrcodeurl.='N:'.$c->contactname.';';
		//Adresa
		//$qrcodeurl.='ADR:smith%20st,new%20york;';
		//Tel Mobil
		$qrcodeurl.='TEL:'.$c->mobile.';';
		//Tel Fix
		$qrcodeurl.='TEL:'.$c->phone.';';
		//Email
		$qrcodeurl.='EMAIL:'.$c->email.';';
		//Url
		$qrcodeurl.='URL:'.urlencode($this->getServerName().$this->getRequestURI()).';';	
		//Note
		//$qrcodeurl.='NOTE:'.$c->notecontact.'';
		
		$out='';
		$out.='<table class="property-table" style="width:100%;align:center;">';
		$out.='<tr>';
		$out.='<td class="property-value" style="text-align: center;">Salveaza contactele pe telefon:</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-value"><img src="'.$qrcodeurl.'" alt="qr code" class="imageborder"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-value" style="text-align: center;"><a href="https://www.youtube.com/watch?v=GOgyC8liCfg" target="_blank">Ce este un QR code?</a></td>';
		$out.='</tr>';
		$out.='</table>';
	
		return $out;
	}	
	
	function setFiles(){
		///manage added files
		$files=User::getCurrentFiles();
		if((is_array($_FILES))&&(sizeof($_FILES)!=0)){
		  	 $filescounter=0;
		  	 for ($i = 0; $i <= 4; $i++) {
		  	 	if ($_POST['status'][$i]!=1){
		  	 		//delete previously add file if added
		  	 		
		  	 		if (isset($files[$i]->imagepath)){
		  	 				unlink(Config::$filespath."/".$files[$i]->imagepath);
		  	 				if ((isset($files[$i]->id))){
		  	 					$files[$i]->delete();
		  	 					unlink(Config::$filespath."/t".$files[$i]->imagepath);
		  	 				}
		  	 				$f=new Image();
		  	 				$files[$i]=$f;
		  	 		}
		  	 		
		  	 		//add files
		  	 		$files[$i]->imagename=$_FILES["file"]["name"][$filescounter];
		  	 		$files[$i]->name=$_FILES["file"]["name"][$filescounter];		  	 		
		  	 		//$files[$i]->imagenote=$this->imagenote[$filescounter];
		  	 		$files[$i]->imagenote='';
		  	 		$files[$i]->type=$_FILES["file"]["type"][$filescounter];
		  	 		$files[$i]->size=$_FILES["file"]["size"][$filescounter];
		  	 		$files[$i]->tmp_name=$_FILES["file"]["tmp_name"][$filescounter];
		  	 		if ($_FILES["file"]["error"][$filescounter]!=0){
		  	 			$files[$i]->error=$_FILES["file"]["error"][$filescounter];
		  	 		}elseif (!array_key_exists($_FILES["file"]["type"][$filescounter], Config::$file_types)){
		  	 			$files[$i]->error=8;
		  	 		}elseif($_FILES["file"]["size"][$filescounter]>Config::$file_size){
		  	 			$files[$i]->error=1;
		  	 		}else {
		  	 			$files[$i]->error=$_FILES["file"]["error"][$filescounter];
		  	 			$files[$i]->new_name=System::getRandomFileName($files[$i]->name);
		  	 			$files[$i]->imagepath=$files[$i]->new_name;
        				move_uploaded_file($files[$i]->tmp_name, Config::$filespath."/".$files[$i]->new_name);
		  	 		}
		  	 		$filescounter=$filescounter+1;
		  	 	}
		  	}
			User::setCurrentFiles($files);
		}			
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Imagini:</legend>';
		$out.='<div id="msg" class="form_row" style="padding-bottom:20px;">';
		$out.='Atentie ! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.';	
		$out.='</div>';	
		$out.='<div id="filestable" class="form_row">'; 	  
		$out.='<table id="files" border1=1 style1="width:100%;" width="100%">';
		if ((sizeof($files)!=0)){
		    for ($i = 0; $i <= 4; $i++) {
		    	if (isset($files[$i]->error)){
			    	if ($files[$i]->error==UPLOAD_ERR_OK){
			        	$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).':<input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="1"></td><td style="text-align: left;"> ['.$files[$i]->imagename.']</td><td style="text-align: left;"><a onclick="ReplaceRow(this.parentNode.parentNode.rowIndex)" style="text-decoration: underline;cursor: pointer;color:blue;">sterge</a></td></tr>';
			        	//$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).', descriere:<input type="text" id="imagenote[]" name="imagenote[]" style="width:70%" value="'.$files[$i]->imagenote.'"><input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="1"></td><td style="text-align: left;"> ['.$files[$i]->imagename.']</td><td style="text-align: left;"><a onclick="ReplaceRow(this.parentNode.parentNode.rowIndex)">sterge</a></td></tr>';
			    	}else {
			    		$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).':<input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="0"></td><td style="text-align: left;"><input type="file" id="file[]" name="file[]" style="width:50%;"></td><td style="text-align: left;">'.Image::getErrorMsg($files[$i]->imagename,$files[$i]->error).'</td></tr>';
			    		//$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).', descriere:<input type="text" id="imagenote[]" name="imagenote[]" style="width:70%" value="'.$files[$i]->imagenote.'"><input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="0"></td><td style="text-align: left;"><input type="file" id="file[]" name="file[]" ></td><td style="text-align: left;">'.Image::getErrorMsg($files[$i]->imagename,$files[$i]->error).'</td></tr>';
			    	}
		    	} else {
		    		$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).':<input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="0"></td><td style="text-align: left;"><input type="file" id="file[]" name="file[]" style="width:50%;"></td><td></td></tr>';
		    		//$out.='<tr><td style="text-align: right;">Imaginea '.($i+1).', descriere:<input type="text" id="imagenote[]" name="imagenote[]" style="width:70%" value="'.$files[$i]->imagenote.'"><input type="hidden" id="fileid[]" name="fileid[]" value="'.$i.'"><input type="hidden" id="status[]" name="status[]" value="0"></td><td style="text-align: left;"><input type="file" id="file[]" name="file[]" ></td><td></td></tr>';
		    	}
			}
		}
		$out.='</table>';
		$out.='</div>';
		$out.='<div id="filestable" class="form_row" style="text-align: right;">'; 		
		$out.='<input name="back" type="button" class="button" value="Upload" onclick="javascript:WizardUploadButtonOnClick();">';
		$out.='</div>';		
		//$out.='</fieldset>';
		return $out;	 
	}
	function getFiles(){
		$files=User::getCurrentFiles();
	
		//$this->setCSS(Config::$commonsite."/style/lightbox.css");
	
		//$this->setCSS(Config::$commonsite."/js/lightbox2.51/css/screen.css");
		//$this->setCSS("https://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:400,700");
		
		
		//$this->setJavascript(Config::$commonsite."/js/builder.js");
		//$this->setJavascript(Config::$commonsite."/js/effects.js");

		//$this->setJavascript(Config::$commonsite."/js/prototype.js");
		//$this->setJavascript(Config::$commonsite."/js/scriptaculous.js?load=effects,builder");
		//$this->setJavascript(Config::$commonsite."/js/lightbox.js");
		
		//$this->setJavascript(Config::$commonsite."/js/scriptaculous-js-1.9.0/prototype.js");
		//$this->setJavascript(Config::$commonsite."/js/scriptaculous-js-1.9.0/scriptaculous.js");		
		//$this->setJavascript(Config::$commonsite."/js/lightbox v2.04/lightbox.js");
		
		//$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
		//$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js");
		//2.51
		//$this->setCSS(Config::$commonsite."/js/lightbox2.51/css/lightbox.css");
		//$this->setJavascript(Config::$commonsite."/js/lightbox2.51/js/jquery-1.7.2.min.js");
		//$this->setJavascript(Config::$commonsite."/js/lightbox2.51/js/jquery-ui-1.8.18.custom.min.js");
		//$this->setJavascript(Config::$commonsite."/js/lightbox2.51/js/jquery.smooth-scroll.min.js");				
		//$this->setJavascript(Config::$commonsite."/js/lightbox2.51/js/lightbox.js");
		//2
		$this->setCSS(Config::$commonsite."/js/lightbox2/css/lightbox.css");
		$this->setFooterJavascript(Config::$commonsite."/js/lightbox2/js/lightbox-plus-jquery.min.js");
		
		
		$out='';
		$out.='<table class="property-table" style="width: 100%;align:center;">';
		$out.='<tr><td style="text-align:center;">';
		if ((sizeof($files)!=0)){
			$outt='';
			foreach ($files as $i){
				if (isset($i->imagepath)){
					//$outt.='<a href="'.Config::$filespath.'/'.$i->imagepath.'" rel="lightbox[list]"><img src="'.Config::$filespath.'/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"></a>&nbsp';
					//$outt.='<a href="'.Config::$filespath.'/'.$i->imagepath.'" rel="lightbox[roadtrip]"><img src="'.Config::$filespath.'/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"></a>&nbsp';
					$outt.='<a href="'.Config::$filespath.'/'.$i->imagepath.'" data-lightbox="roadtrip"><img src="'.Config::$filespath.'/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"></a>&nbsp';
				}				
		    }
		    if ($outt==''){
		    	$outt='Imagini nu exista.';
		    } 
			$out.=$outt;
		}
		$out.='</td></tr>';
		$out.='</table>';
		return $out;		
	}					
	function getWizardPage($html){
		$this->setJavascript(Config::$commonsite."/js/wizard.js");
		//$out.='<div id="form" class="font-size: 16px;">';
		$outh='<form id="frmWizard" name="frmWizard" method="POST" enctype="multipart/form-data">';		
		//footer
		$outh.=' <div id="form_header" style1="padding:10px;border-bottom:2px solid #777;font-size: 20px;">';
		$outh.='  <div style="float:left">';	
		$outh.=$this->steptitle;
		$outh.='  </div>';
		$outh.='  <div style="float:right">';
		$outh.='  Pasul '.$this->step.' din '.$this->steps;		
		$outh.='  </div>';
		$outh.='  <div style="clear: both;"></div>';				
		$outh.=' </div>';
		//body
		$out=' <div id="formcontrols" style="padding:10px;padding-top:20px;padding-bottom:20px;">';
		$out.=$html;
		$out.=' </div>';
		//footer
		$outf=' <div id="form_footer" style1="padding:10px;border-top:2px solid #777;">';
		$outf.='  <div style="float:left">';
		$outf.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		$outf.='  </div>';
		$outf.='  <div style="float:right">';
		if ($this->step!=1){
			$outf.='  <input name="prev" type="submit" class="button" value="<< Inapoi">';
		}
		if ($this->step==$this->steps){
			$outf.='   <input name="save" type="submit" class="button" value="Publica">';
		} else {
			$outf.='   <input name="next" type="submit" class="button" value="Inainte >>">';
		}
		$outf.='  </div>';
		$outf.='  <div style="clear: both;"></div>';
		$outf.=' </div>';				
		$outf.='</form>';
		//$out.='</div>';
		return $this->getGroupBoxWizard($outh,$out,$outf);
	}
	function getQuestionPage($html){
		//$out.='<div id="form" class="font-size: 16px;">';
		$outh.='<form id="frmWizard" name="frmWizard" method="POST" enctype="multipart/form-data">';		
		//footer
		$outh.=' <div id="form_header">';
		$outh.='  <div style="float:left">';	
		$outh.=$this->steptitle;
		$outh.='  </div>';
		//$outh.='  <div style="float:right">';
		//$outh.='  Pasul '.$this->step.' din '.$this->steps;		
		//$outh.='  </div>';
		$outh.='  <div s></div>';				
		$outh.=' </div>';
		//body
		$out.=' <div id="formcontrols" style="padding:10px;padding-top:20px;padding-bottom:20px;height:160px;width:auto;">';
		$out.=$html;
		$out.=' </div>';
		//footer
		$outf.=' <div id="form_footer" style1="padding:10px;border-top:2px solid #777;">';
		//$outf.='  <div style="float:left">';
		//$outf.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		//$outf.='  </div>';
		$outf.='  <div style="float:right">';
		//if ($this->step!=1){
		$outf.='  <input name="nu" type="submit" class="button" value="Nu">';
		//}
		///if ($this->step==$this->steps){
		$outf.='   <input name="da" type="submit" class="button" value="Da">';
		//} else {
		//	$outf.='   <input name="next" type="submit" class="button" value="Inainte >>">';
		//}
		$outf.='  </div>';
		$outf.='  <div style="clear: both;"></div>';
		$outf.=' </div>';				
		$outf.='</form>';
		//$out.='</div>';
		return $this->getGroupBoxDialog($outh,$out,$outf);
	}
	function getSearchLocation(){
		$lsrs="";
		
		if ((!empty($_POST['searchlocationformpost']))&&(isset($this->lsearch))){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".mysql_real_escape_string($this->lsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			if (!is_null($ls)){
				foreach ($ls as $v){
					$l->loadById($v->location_id);
					$r->loadById($l->raion_id);
					$lurl=$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$l->id);
					$lsrs.="<a href=".$lurl." target=\"_blank\">".$r->getFullName()."->".$l->getFullName()."</a><br>";
				}
			}else {
				$lsrs="Localitati *".$this->lsearch."* nu exista!";
			}
		}
		$out='<div id="searchLocation">';
		$out.='<form id="searchlocationform" name="searchlocationform" method="post">';
		$out.='<div id="location-search-box"><input type="text" name="lsearch" value="'.(isset($this->lsearch)?$this->lsearch:'').'"><br><input type="submit" name="searchlocationformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}
	function getSearchDictionar(){
		$out='<div id="dictionar">';
		$out.='<form id="searchdictionarformpost" name="searchdictionarformpost" method="get">';
		$out.='<div id="location-search-box1"><input type="text" name="dsearch" value=""><input type="submit" name="searchdictionarformpost" class="button" style="width:60px;" value="Cauta"></div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}
	function getMainSearchDictionar(){
		$lsrs="";
	
		if ((!empty($_GET['searchdictionarformpost']))&&(isset($this->dsearch))){
			$ls=DBManager::doSql("SELECT id, descriere,denumire,tip,judet  FROM `dictionar` WHERE `dictionar`.deleted=0 AND `dictionar`.descriere like '%".mysql_real_escape_string($this->dsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			$lsrs.="Rezultatele cautarii:<br><br>";
			if (!is_null($ls)){
				foreach ($ls as $v){
					$lurl=$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php","action=viewdictionar&id=".$v->id);
					$lsrs.='<a href="'.$lurl.'">'.$v->denumire.', '.$v->tip.' in Judetul '.$v->judet.'</a><br>';
				}
			}else {
				$lsrs.="Cuvintul *".System::getHTML($this->dsearch)."* nu exista in dictionar!";
			}
		}
		$out='<div id="dictionar" style="text-align:center;">';
		$out.='<form id="searchdictionarformpost" name="searchdictionarformpost" method="GET">';
		$out.='<div id="location-search-box1"><br><br><br><input type="text" name="dsearch"  style="width:400px;" value="'.(isset($this->dsearch)?$this->dsearch:'').'"><input type="submit" name="searchdictionarformpost" class="button" style="width:60px;" value="Cauta"></div>';
		$out.='<div id="location-search-box1" style="text-align:left;padding-left:10px;"><br><br><br>'.$lsrs.'<br><br><br></div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}		
	function getSearchPrimarie(){
		$lsrs="";
	
		if ((!empty($_POST['searchprimarieformpost']))&&(isset($this->psearch))){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` inner join primari2011 on localitate.id=primari2011.localitate_id WHERE `localitate`.deleted=0 AND `localitate`.name like '%".mysql_real_escape_string($this->psearch,DBConnection::getConnection())."%' LIMIT 0,30");
			if (!is_null($ls)){
				foreach ($ls as $v){
					$l->loadById($v->location_id);
					$r->loadById($l->raion_id);
					$lurl=$this->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewprimarie&id=".$l->id);
					$lsrs.="<a href=".$lurl." target=\"_blank\">".$r->getFullName()."->".$l->getFullName()."</a><br>";
				}
			}else {
				$lsrs="Localitati *".System::getHTML($this->psearch)."* nu exista!";
			}
		}
		$out='<div id="searchPrimarie">';
		$out.='<form id="searchprimarieformpost" name="searchprimarieformpost" method="post">';
		$out.='<div id="primaria-search-box"><input type="text" name="psearch" value="'.(isset($this->psearch)?$this->psearch:'').'"><br><input type="submit" name="searchprimarieformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}
	function getSearchName(){
		$nsrs="";
	
		if ((!empty($_POST['searchnameformpost']))&&(isset($this->nsearch))){
			$l=new Location();
			$r=new Raion();
			//$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".mysql_real_escape_string($this->lsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			$ns=DBManager::doSql("SELECT id,name FROM `family` WHERE deleted=0 and name like '".mysql_real_escape_string($this->nsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			if (!is_null($ns)){
				foreach ($ns as $v){
					//$l->loadById($v->location_id);
					//$r->loadById($l->raion_id);
					$nurl=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$v->id);
					$nsrs.="<a href=".$nurl." target=\"_blank\">".$v->name."</a><br>";
				}
			}else {
				$nsrs="Numele de familie *".$this->nsearch."* nu exista!";
			}
		}
		$out='<div id="name">';
		$out.='<form id="searchnameform" name="searchnameform" method="post">';
		$out.='<div id="location-search-box1"><input type="text" name="nsearch" value="'.(isset($this->nsearch)?$this->nsearch:'').'"><br><input type="submit" name="searchnameformpost" class="button" style="width:60px;" value="Cauta"><br>'.$nsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}			
	function getPanoramioFotos($m){
	
		$this->setJavascript("https://www.panoramio.com/wapi/wapi.js?v=1");
		$this->setJavascript(Config::$commonsite."/js/panoramio.js");
	
		if ($m->lat==0){
			$m->maptype=3;
			$m->lat=0;
			$m->lng=0;
		}
		$out='<div id="div_photo_ex" class="container groupbox"></div>';
		$out.='<script type="text/javascript">';
		//$out.='showFotos(46.0074,28.4152);';
		$out.='showFotos('.$m->lat.','.$m->lng.');';
		$out.='</script>';
		return $out;
	}
	function getSkypeControl($skypeid){
		$out='';
// 		if ($skypeid!=""){
// 			$this->setJavascript("https://cdn.dev.skype.com/uri/skype-uri.js");
// 			$out.='<div id="call_16" style1="width:20%;background-color:#0094ff">';
// 			$out.='<script type="text/javascript">';
// 			$out.='Skype.ui({name: "call",element: "call_16",participants: ["echo123"],imageSize: 16,imageColor: "white"});';
// 			$out.='</script>';
// 			$out.='</div>';		
// 		}
		if ($skypeid!=""){
			$out.=' <a href="skype:'.$skypeid.'?call">'.$skypeid.'</a>';
		}		
		return $out;
	}
	function getEmailControl($emailid){
		$out='';
		if ($emailid!=""){
			$out.=' <a href="mailto:'.$emailid.'">'.$emailid.'</a>';
		}
		return $out;
	}
	function getUrlControl($urlid){
		$out='';
		if ($urlid!=""){
			$out.=' <a href="'.System::getValidUrl($urlid).'" target="_blank">'.$urlid.'</a>';
		}
		return $out;
	}
	function actionViewXMLPois(){
		$this->setContentType("text/xml");		
		WebPage::show($this->getXml());
	}
	function getXml(){
	
	
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<markers>';
	
		$m=new Map();
		$ms=$m->getAll();
		foreach($ms as $m){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($m->title).'" ';
			$out.='description="'.$this->parseToXML($m->description).'" ';
			$out.='lat="'.$m->lat.'" ';
			$out.='lng="'.$m->lng.'" ';
			$out.='type="link" ';
			$out.='link="'.htmlspecialchars($m->getUrl(Config::$mapssite.'/index.php','action=viewmap&id='.$m->id)).'" ';
			//$out.='link="'.Config::$mapssite.'/index.php?action=viewmap&id='.$m->id.'" ';
			$out.='/>';
		}
	
		$n=new News();
		$ns=$n->getAll("lat!=\"\"");
		foreach($ns as $n){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($n->title).'" ';
			$out.='description="'.$this->parseToXML($n->description).'" ';
			$out.='lat="'.$n->lat.'" ';
			$out.='lng="'.$n->lng.'" ';
			$out.='type="news" ';
			$out.='link="'.Config::$newssite.'/index.php?id='.$n->id.'" ';
			$out.='/>';
		}
	
		$p=new Property();
		$ps=$p->getAll("lat!=\"\"");
		foreach($ps as $p){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($p->getShortDescription()).'" ';
			$out.='description="'.$this->parseToXML($p->getShortDescription()).'" ';
			$out.='lat="'.$p->lat.'" ';
			$out.='lng="'.$p->lng.'" ';
			if ($p->scop_id==1){
				$out.='type="imobil" ';
				$out.='link="'.Config::$imobilsite.'/property.php?id='.$p->id.'" ';
			}else {
				$out.='type="chirie" ';
				$out.='link="'.Config::$chiriesite.'/property.php?id='.$p->id.'" ';
			}
			$out.='/>';
		}
		$p=new Photo();
		$ps=$p->getAll();
		foreach($ps as $p){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($p->title).'" ';
			$out.='description="'.$this->parseToXML($p->note).'" ';
			$out.='lat="'.$p->lat.'" ';
			$out.='lng="'.$p->lng.'" ';
			$out.='type="photo" ';
			$out.='link="'.Config::$imagessite.'/index.php?id='.$p->id.'" ';
			$out.='/>';
		}
	
		$out.='</markers>';
		return $out;
	}
	function parseToXML($htmlStr){
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}
	public function getFooterWithLink($link){
		$out='';
		$out.='<div style="text-align: right;  margin: 10px;">';
		$out.='<a class="link_button" href="'.$link.'" target="_blank">Vezi aici mai multe</a>';
		$out.='</div>';	
		return $out;
	}
	public function getAddMenu(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/add.php').'">Adaugă Imobil</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$chiriesite.'/add.php').'">Adaugă Chirie</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/add.php').'">Adaugă Companie</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/add.php').'">Adaugă Foto</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/add.php').'">Adaugă Telefon</a></li>';
		$out.='</ul>';
		return $out;
	}
	public function getPrivescEuWidget(){
		
		$out='<iframe src="//www.privesc.eu/Widget/embeded/�nregistrare/Moldova,Parlament,Guvern,Conferinte,Offlineuri,Emisiuni,RIA,Concerte,Retransmisiuni,Sport,Monden,Altele," frameborder="0" width="180" height="140" scrolling="no" title="Televizor Privesc.Eu"></iframe>';
		
		return $this->getGroupBoxH2("Privesc Eu TV",$out);
	}
	public function setOGP($title, $type, $url, $image){
		$this->ogp=array(
			"title" => $title,
			"type" => $type,
			"url" => $url,
			"image" => $image
		);
	}	
	public function getOGP(){
		$out = '';
		foreach ((array)$this->ogp as $key => $value) {
			$out.='<meta property="og:'.$key.'" content="'.$value.'" />';
		}
		return $out;
	}	
}
//$b=new WebPage();
//phpinfo();
?>