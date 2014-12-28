<?php
/*
 * Created on 26 Feb 2009
 *
 */
class TopMenu {
	public static function getItems($language){
		$Items=array(
		Config::$mainsite."/index.php?l=".$language->name=>"Acasă",
		Config::$imobilsite."/index.php?l=".$language->name=>"Imobil",
		Config::$chiriesite."/index.php?l=".$language->name=>"Chirie",
		Config::$companiesite."/index.php?l=".$language->name=>"Companii",
		Config::$newssite."/index.php?l=".$language->name=>"Ştiri",
		//Config::$videosite."/index.php?l=".$language->name=>"Video",		
		Config::$imagessite."/index.php?l=".$language->name=>"Imagini",
		Config::$mapssite."/index.php?l=".$language->name=>"Hărţi",	
		Config::$locationssite."/index.php?l=".$language->name=>"Localități",
		Config::$primariisite."/index.php?l=".$language->name=>"Primarii",
		Config::$distantesite."/index.php?l=".$language->name=>"Distanțe",
		Config::$dictionarsite."/index.php?l=".$language->name=>"Dictionar",
		Config::$numesite."/index.php?l=".$language->name=>"Nume",			
		Config::$telefoanesite."/index.php?l=".$language->name=>"Telefoane"	
		);
		return $Items;
	}
	public static function getSelectedItem1(){
		Logger::setLogs($_SERVER["REQUEST_URI"]);
		$filename=explode("?",$_SERVER["REQUEST_URI"]);
		return str_replace("/","",$filename[0]);
	}
}
?>
