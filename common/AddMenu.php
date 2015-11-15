<?php
class AddMenu {
	public static function getItems(){
		$Items=array(
		"Adauga Imobil"=>Config::$imobilsite.'/add.php',
		"Adauga Chirie"=>Config::$chiriesite.'/add.php',
		"Adauga Companie"=>Config::$companiesite.'/add.php',
		"Adauga Foto"=>Config::$imagessite.'/add.php',
		"Adauga Harta"=>Config::$mapssite.'/add.php',
		"Adauga Stire"=>Config::$newssite.'/add.php'
		);
		return $Items;
	}	

}
?>