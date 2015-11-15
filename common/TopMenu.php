
<?php
class TopMenu {
	public static function getItems($language){
		$Items=array(
		"Imobil"=>array(
		"Imobil"=>Config::$imobilsite."/index.php?l=".$language->name,
		"Chirie"=>Config::$chiriesite."/index.php?l=".$language->name
		),
		"Companii"=>Config::$companiesite."/index.php?l=".$language->name,
		"Ştiri"=>array(
		"Ştiri"=>Config::$newssite."/index.php?l=".$language->name,
		"Feeduri"=>Config::$feedssite."/index.php?l=".$language->name
		),
		//"Video"=>Config::$videosite."/index.php?l=".$language->name,		
		"Imagini"=>Config::$imagessite."/index.php?l=".$language->name,
		"Hărţi"=>array(
		"Hărţi"=>Config::$mapssite."/index.php?l=".$language->name,	
		"Distanțe"=>Config::$distantesite."/index.php?l=".$language->name
		),
		"Localități"=>array(
		"Localități"=>Config::$locationssite."/index.php?l=".$language->name,
		"Primarii"=>Config::$primariisite."/index.php?l=".$language->name
		),
		
		"Dictionar"=>array(
		"Dictionar Localitati"=>Config::$dictionarsite."/index.php?l=".$language->name,
		"Dictionar Nume"=>Config::$numesite."/index.php?l=".$language->name,
		"Telefoane"=>Config::$telefoanesite."/index.php?l=".$language->name
		)		
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

