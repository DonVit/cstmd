<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
/*
 * This page is to redirect urls like http://anunt.casata.md/imobil.php?fscop=1&ftip=0&fraionid=100&flocalitateid=0&fsectorid=0&fsubtipimobilid=0 to news urls
 */
class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
									
		$link=Config::$imobilsite;

		$this->redirect($link);
	}
}
$n=new IndexWebPage();
?>
